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
        $signature = md5($merchantCode . $merchantOrderId . $amount . $apiKey);

        $customer = $invoice->customer;

        $payload = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => $options['payment_channel'] ?? 'VC', // Default or specific channel
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Pembayaran Tagihan Internet ' . $invoice->invoice_number,
            'email' => $customer->email ?? 'billing@domain.com',
            'phoneNumber' => $customer->phone,
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
                    paymentChannel: $data['paymentMethod'] ?? 'DUITKU',
                    rawResponse: $data
                );
            }

            return new PaymentRedirectResponse(
                success: false,
                errorMessage: $data['statusMessage'] ?? 'Gagal membuat pembayaran di Duitku.',
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
            rawPayload: $request->all()
        );
    }
}
