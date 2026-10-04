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

class TripayGateway implements PaymentGatewayInterface
{
    public function createPayment(Invoice $invoice, PaymentMethod $method, array $options = []): PaymentRedirectResponse
    {
        $credentials = $method->credentials ?? [];
        $merchantCode = $credentials['merchant_code'] ?? '';
        $apiKey = $credentials['api_key'] ?? '';
        $privateKey = $credentials['private_key'] ?? '';
        $isSandbox = ($credentials['environment'] ?? 'sandbox') === 'sandbox';

        $endpoint = $isSandbox
            ? 'https://tripay.co.id/api-sandbox/transaction/create'
            : 'https://tripay.co.id/api/transaction/create';

        $merchantRef = $invoice->invoice_number;
        $amount = (int) round($invoice->total_amount);
        $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);
        $customer = $invoice->customer;

        $payload = [
            'method' => $options['payment_channel'] ?? 'QRIS2', // Default or specific channel
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email ?? 'customer@mwifi.id',
            'customer_phone' => $customer->phone ?: '081234567890',
            'order_items' => [
                [
                    'name' => 'Paket Internet ' . ($customer->package?->name ?? 'Bulanan'),
                    'price' => $amount,
                    'quantity' => 1,
                ],
            ],
            'callback_url' => url('/api/webhooks/tripay'),
            'return_url' => url('/pay/' . $invoice->payment_token),
            'expired_time' => time() + (24 * 3600),
            'signature' => $signature,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(10)->post($endpoint, $payload);

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false) && isset($data['data'])) {
                $trxData = $data['data'];
                return new PaymentRedirectResponse(
                    success: true,
                    paymentUrl: $trxData['checkout_url'] ?? null,
                    providerReference: $trxData['reference'] ?? null,
                    paymentChannel: $trxData['payment_name'] ?? 'TRIPAY',
                    rawResponse: $data
                );
            }

            return new PaymentRedirectResponse(
                success: false,
                errorMessage: $data['message'] ?? 'Gagal membuat transaksi di Tripay.',
                rawResponse: $data ?? []
            );
        } catch (\Exception $e) {
            Log::error('Tripay createPayment exception: ' . $e->getMessage());
            return new PaymentRedirectResponse(
                success: false,
                errorMessage: 'Koneksi ke Tripay gagal: ' . $e->getMessage(),
                rawResponse: ['exception' => $e->getMessage()]
            );
        }
    }

    public function verifyWebhook(Request $request, PaymentMethod $method): bool
    {
        $privateKey = $method->credentials['private_key'] ?? '';
        $callbackSignature = $request->header('X-Callback-Signature');
        $rawContent = $request->getContent();

        if (empty($privateKey) || empty($callbackSignature) || empty($rawContent)) {
            return false;
        }

        $calculatedSignature = hash_hmac('sha256', $rawContent, $privateKey);
        return hash_equals($calculatedSignature, $callbackSignature);
    }

    public function handleWebhook(Request $request, PaymentMethod $method): WebhookResult
    {
        $isValid = $this->verifyWebhook($request, $method);
        if (!$isValid) {
            return new WebhookResult(
                isValid: false,
                provider: 'TRIPAY',
                merchantOrderId: $request->input('merchant_ref', ''),
                providerReference: $request->input('reference', ''),
                amount: (float) $request->input('total_amount', 0),
                status: 'FAILED',
                message: 'Tripay X-Callback-Signature verification failed',
                errorMessage: 'Tripay X-Callback-Signature verification failed',
                rawPayload: $request->all()
            );
        }

        $rawStatus = strtoupper($request->input('status', ''));
        $status = match ($rawStatus) {
            'PAID' => 'SUCCESS',
            'EXPIRED', 'FAILED' => 'FAILED',
            default => 'PENDING',
        };

        return new WebhookResult(
            isValid: true,
            provider: 'TRIPAY',
            merchantOrderId: $request->input('merchant_ref'),
            providerReference: $request->input('reference'),
            amount: (float) $request->input('total_amount'),
            status: $status,
            message: 'Webhook processed successfully',
            channel: $request->input('payment_method', 'TRIPAY'),
            paidAt: $status === 'SUCCESS' ? now() : null,
            rawPayload: $request->all()
        );
    }
}
