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

class XenditGateway implements PaymentGatewayInterface
{
    public function createPayment(Invoice $invoice, PaymentMethod $method, array $options = []): PaymentRedirectResponse
    {
        $credentials = $method->credentials ?? [];
        $secretKey = $credentials['secret_key'] ?? '';

        $endpoint = 'https://api.xendit.co/v2/invoices';
        $orderId = $invoice->invoice_number;
        $amount = (int) round($invoice->total_amount);
        $customer = $invoice->customer;

        $payload = [
            'external_id' => $orderId,
            'amount' => $amount,
            'payer_email' => $customer->email ?? 'customer@mwifi.id',
            'description' => 'Tagihan Internet ' . $invoice->invoice_number . ' - ' . ($invoice->tenant?->name ?? 'MWIFI'),
            'invoice_duration' => 86400, // 24 hours
            'customer' => [
                'given_names' => $customer->name,
                'email' => $customer->email ?? 'customer@mwifi.id',
                'mobile_number' => $customer->phone,
            ],
            'success_redirect_url' => url('/pay/' . $invoice->payment_token),
            'failure_redirect_url' => url('/pay/' . $invoice->payment_token),
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
            ])->timeout(10)->post($endpoint, $payload);

            $data = $response->json();

            if ($response->successful() && isset($data['invoice_url'])) {
                return new PaymentRedirectResponse(
                    success: true,
                    paymentUrl: $data['invoice_url'],
                    providerReference: $data['id'] ?? null,
                    paymentChannel: 'XENDIT',
                    rawResponse: $data
                );
            }

            return new PaymentRedirectResponse(
                success: false,
                errorMessage: $data['message'] ?? 'Gagal membuat invoice di Xendit.',
                rawResponse: $data ?? []
            );
        } catch (\Exception $e) {
            Log::error('Xendit createPayment exception: ' . $e->getMessage());
            return new PaymentRedirectResponse(
                success: false,
                errorMessage: 'Koneksi ke Xendit gagal: ' . $e->getMessage(),
                rawResponse: ['exception' => $e->getMessage()]
            );
        }
    }

    public function verifyWebhook(Request $request, PaymentMethod $method): bool
    {
        $webhookToken = $method->credentials['webhook_token'] ?? '';
        $callbackToken = $request->header('x-callback-token');

        if (empty($webhookToken) || empty($callbackToken)) {
            return false;
        }

        return hash_equals($webhookToken, $callbackToken);
    }

    public function handleWebhook(Request $request, PaymentMethod $method): WebhookResult
    {
        $isValid = $this->verifyWebhook($request, $method);
        if (!$isValid) {
            return new WebhookResult(
                isValid: false,
                status: 'FAILED',
                orderId: $request->input('external_id', ''),
                amount: (float) $request->input('amount', 0),
                providerReference: $request->input('id', ''),
                errorMessage: 'Xendit x-callback-token verification failed'
            );
        }

        $rawStatus = strtoupper($request->input('status', ''));
        $status = match ($rawStatus) {
            'PAID', 'SETTLED' => 'PAID',
            'EXPIRED' => 'FAILED',
            default => 'PENDING',
        };

        return new WebhookResult(
            isValid: true,
            status: $status,
            orderId: $request->input('external_id'),
            amount: (float) $request->input('amount'),
            providerReference: $request->input('id'),
            channel: $request->input('payment_method', 'XENDIT'),
            rawPayload: $request->all(),
            paidAt: $status === 'PAID' ? now() : null
        );
    }
}
