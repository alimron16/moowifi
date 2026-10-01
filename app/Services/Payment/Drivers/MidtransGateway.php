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

class MidtransGateway implements PaymentGatewayInterface
{
    public function createPayment(Invoice $invoice, PaymentMethod $method, array $options = []): PaymentRedirectResponse
    {
        $credentials = $method->credentials ?? [];
        $serverKey = $credentials['server_key'] ?? '';
        $isSandbox = ($credentials['environment'] ?? 'sandbox') === 'sandbox';

        $endpoint = $isSandbox
            ? 'https://app.sandbox.midtrans.com/snap/v1/transactions'
            : 'https://app.midtrans.com/snap/v1/transactions';

        $orderId = $invoice->invoice_number;
        $amount = (int) round($invoice->total_amount);
        $customer = $invoice->customer;

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email ?? 'customer@mwifi.id',
                'phone' => $customer->phone,
            ],
            'item_details' => [
                [
                    'id' => 'INV-' . $invoice->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Langganan Internet ' . ($customer->package?->name ?? 'Bulanan'),
                ],
            ],
            'callbacks' => [
                'finish' => url('/pay/' . $invoice->payment_token),
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
            ])->timeout(10)->post($endpoint, $payload);

            $data = $response->json();

            if ($response->successful() && isset($data['redirect_url'])) {
                return new PaymentRedirectResponse(
                    success: true,
                    paymentUrl: $data['redirect_url'],
                    providerReference: $data['token'] ?? null,
                    paymentChannel: 'MIDTRANS',
                    rawResponse: $data
                );
            }

            return new PaymentRedirectResponse(
                success: false,
                errorMessage: $data['error_messages'][0] ?? 'Gagal membuat transaksi di Midtrans Snap.',
                rawResponse: $data ?? []
            );
        } catch (\Exception $e) {
            Log::error('Midtrans createPayment exception: ' . $e->getMessage());
            return new PaymentRedirectResponse(
                success: false,
                errorMessage: 'Koneksi ke Midtrans gagal: ' . $e->getMessage(),
                rawResponse: ['exception' => $e->getMessage()]
            );
        }
    }

    public function verifyWebhook(Request $request, PaymentMethod $method): bool
    {
        $serverKey = $method->credentials['server_key'] ?? '';
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return false;
        }

        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($calculatedSignature, $signatureKey);
    }

    public function handleWebhook(Request $request, PaymentMethod $method): WebhookResult
    {
        $isValid = $this->verifyWebhook($request, $method);
        if (!$isValid) {
            return new WebhookResult(
                isValid: false,
                status: 'FAILED',
                orderId: $request->input('order_id', ''),
                amount: (float) $request->input('gross_amount', 0),
                providerReference: $request->input('transaction_id', ''),
                errorMessage: 'Midtrans signature verification failed'
            );
        }

        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        $status = 'PENDING';
        if ($transactionStatus === 'capture') {
            $status = ($fraudStatus === 'challenge') ? 'PENDING' : 'PAID';
        } elseif ($transactionStatus === 'settlement') {
            $status = 'PAID';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $status = 'FAILED';
        }

        return new WebhookResult(
            isValid: true,
            status: $status,
            orderId: $request->input('order_id'),
            amount: (float) $request->input('gross_amount'),
            providerReference: $request->input('transaction_id'),
            channel: $request->input('payment_type', 'MIDTRANS'),
            rawPayload: $request->all(),
            paidAt: $status === 'PAID' ? now() : null
        );
    }
}
