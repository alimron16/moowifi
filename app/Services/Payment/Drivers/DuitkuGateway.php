<?php

namespace App\Services\Payment\Drivers;

use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Services\Payment\DTO\PaymentRedirectResponse;
use App\Services\Payment\DTO\WebhookResult;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuGateway implements PaymentGatewayInterface
{
    public function createPayment(Invoice $invoice, PaymentMethod $method, array $options = []): PaymentRedirectResponse
    {
        $credentials = $method->credentials ?? [];
        $merchantCode = $credentials['merchant_code'] ?? '';
        $apiKey = $credentials['api_key'] ?? '';
        $isSandbox = ($credentials['environment'] ?? 'sandbox') === 'sandbox';

        $endpoint = $isSandbox
            ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'
            : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';

        $merchantOrderId = $invoice->invoice_number;
        $amount = (int) round($invoice->total_amount);
        $customer = $invoice->customer;
        $channel = trim((string) ($options['payment_channel'] ?? ''));

        // 1. Coba Duitku POP jika channel kosong (menampilkan semua opsi di Duitku POP)
        if (empty($channel)) {
            $timestamp = (string) round(microtime(true) * 1000);
            $signaturePop = hash_hmac('sha256', $merchantCode . $timestamp, $apiKey);

            $popEndpoint = $isSandbox
                ? 'https://api-sandbox.duitku.com/api/merchant/createInvoice'
                : 'https://api-prod.duitku.com/api/merchant/createInvoice';

            $popPayload = [
                'paymentAmount' => $amount,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => 'Pembayaran Tagihan Internet ' . $invoice->invoice_number,
                'customerVaName' => substr($customer?->name ?? 'Pelanggan', 0, 30),
                'email' => $customer?->email ?? 'billing@domain.com',
                'phoneNumber' => $customer?->phone ?: '081234567890',
                'callbackUrl' => url('/api/webhooks/duitku'),
                'returnUrl' => url('/pay/' . $invoice->payment_token),
                'expiryPeriod' => 1440,
            ];

            try {
                $popResponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-duitku-signature' => $signaturePop,
                    'x-duitku-timestamp' => $timestamp,
                    'x-duitku-merchantcode' => $merchantCode,
                ])->timeout(10)->post($popEndpoint, $popPayload);

                $popData = $popResponse->json();

                if ($popResponse->successful() && !empty($popData['paymentUrl'])) {
                    return new PaymentRedirectResponse(
                        success: true,
                        paymentUrl: $popData['paymentUrl'],
                        providerReference: $popData['reference'] ?? null,
                        paymentChannel: 'DUITKU_POP',
                        rawResponse: $popData
                    );
                }
            } catch (\Exception $e) {
                Log::warning('DuitkuGateway POP createInvoice warning: ' . $e->getMessage());
            }

            // Fallback jika Direct API
            $channel = 'NQ';
        }

        // 2. Direct API inquiry (v2)
        $signature = md5($merchantCode . $merchantOrderId . $amount . $apiKey);

        $payload = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => $channel,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Pembayaran Tagihan Internet ' . $invoice->invoice_number,
            'email' => $customer->email ?? 'billing@domain.com',
            'phoneNumber' => $customer->phone ?: '081234567890',
            'customerVaName' => substr($customer->name, 0, 30),
            'callbackUrl' => url('/api/webhooks/duitku'),
            'returnUrl' => url('/pay/' . $invoice->payment_token),
            'signature' => $signature,
            'expiryPeriod' => 1440, // 24 hours
        ];

        try {
            $response = Http::timeout(10)->post($endpoint, $payload);
            $data = $response->json();

            if ($response->successful() && isset($data['statusCode']) && $data['statusCode'] === '00') {
                return new PaymentRedirectResponse(
                    success: true,
                    paymentUrl: $data['paymentUrl'] ?? null,
                    providerReference: $data['reference'] ?? null,
                    paymentChannel: $data['paymentMethod'] ?? $channel,
                    rawResponse: $data
                );
            }

            return new PaymentRedirectResponse(
                success: false,
                errorMessage: $data['statusMessage'] ?? $data['Message'] ?? 'Gagal membuat pembayaran di Duitku.',
                rawResponse: $data ?? []
            );
        } catch (\Exception $e) {
            Log::error('Duitku createPayment exception: ' . $e->getMessage());
            return new PaymentRedirectResponse(
                success: false,
                errorMessage: 'Terjadi kesalahan komunikasi dengan server Duitku: ' . $e->getMessage()
            );
        }
    }

    public function verifyWebhook(Request $request, PaymentMethod $method): bool
    {
        $credentials = $method->credentials ?? [];
        $merchantCode = $credentials['merchant_code'] ?? '';
        $apiKey = $credentials['api_key'] ?? '';

        $reqMerchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $incomingSignature = $request->input('signature');

        if (!$reqMerchantCode || !$amount || !$merchantOrderId || !$incomingSignature) {
            return false;
        }

        if ($reqMerchantCode !== $merchantCode) {
            return false;
        }

        $calculatedSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        return hash_equals($calculatedSignature, $incomingSignature);
    }

    public function handleWebhook(Request $request, PaymentMethod $method): WebhookResult
    {
        $isValid = $this->verifyWebhook($request, $method);

        if (!$isValid) {
            return new WebhookResult(
                isValid: false,
                provider: 'DUITKU',
                message: 'Invalid signature',
                rawPayload: $request->all()
            );
        }

        $resultCode = $request->input('resultCode');
        $status = ($resultCode === '00') ? 'SUCCESS' : 'FAILED';

        return new WebhookResult(
            isValid: true,
            provider: 'DUITKU',
            merchantOrderId: $request->input('merchantOrderId'),
            providerReference: $request->input('reference'),
            amount: (float) $request->input('amount'),
            status: $status,
            message: $request->input('statusMessage', 'OK'),
            channel: $request->input('paymentCode', 'DUITKU'),
            paidAt: $status === 'SUCCESS' ? now() : null,
            rawPayload: $request->all()
        );
    }
}
