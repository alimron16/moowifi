<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\PlatformSetting;
use App\Models\Subscription;
use App\Models\WebhookLog;
use App\Services\BillingService;
use App\Services\Payment\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $gatewayManager,
        protected BillingService $billingService
    ) {}

    public function handleDuitku(Request $request)
    {
        return $this->processWebhook('DUITKU', $request, function ($result) {
            return response()->json(['status' => '00', 'message' => 'Success']);
        }, function ($code, $msg) {
            return response()->json(['status' => $code, 'message' => $msg], 400);
        });
    }

    public function handleMidtrans(Request $request)
    {
        return $this->processWebhook('MIDTRANS', $request, function ($result) {
            return response()->json(['status' => 'OK']);
        }, function ($code, $msg) {
            return response()->json(['status' => 'error', 'message' => $msg], 400);
        });
    }

    public function handleXendit(Request $request)
    {
        return $this->processWebhook('XENDIT', $request, function ($result) {
            return response()->json(['status' => 'SUCCESS']);
        }, function ($code, $msg) {
            return response()->json(['status' => 'ERROR', 'message' => $msg], 400);
        });
    }

    public function handleTripay(Request $request)
    {
        return $this->processWebhook('TRIPAY', $request, function ($result) {
            return response()->json(['success' => true]);
        }, function ($code, $msg) {
            return response()->json(['success' => false, 'message' => $msg], 400);
        });
    }

    protected function processWebhook(string $provider, Request $request, callable $successResponse, callable $errorResponse)
    {
        // 1. Log incoming raw payload for security audit
        $webhookLog = WebhookLog::create([
            'provider' => $provider,
            'external_reference' => $request->input('reference') ?? $request->input('transaction_id') ?? $request->input('id'),
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'ip_address' => $request->ip(),
            'status' => 'RECEIVED',
        ]);

        try {
            $driver = $this->gatewayManager->driver($provider);
        } catch (\Exception $e) {
            $webhookLog->update(['status' => 'FAILED', 'error_message' => $e->getMessage()]);
            return $errorResponse('01', 'Unsupported gateway provider');
        }

        // Determine Order ID / Invoice Number from request
        $orderId = match ($provider) {
            'DUITKU' => $request->input('merchantOrderId'),
            'MIDTRANS' => $request->input('order_id'),
            'XENDIT' => $request->input('external_id'),
            'TRIPAY' => $request->input('merchant_ref'),
            default => null,
        };

        if (!$orderId) {
            $webhookLog->update(['status' => 'FAILED', 'error_message' => 'Order ID/Invoice tidak ditemukan pada payload.']);
            return $errorResponse('01', 'Order ID not provided');
        }

        // 2. Cek apakah ini pesanan Langganan SaaS Platform (ORD-SUB-...)
        $subscription = Subscription::where('order_number', $orderId)->first();
        if ($subscription) {
            return $this->handleSubscriptionPayment($provider, $request, $subscription, $webhookLog, $successResponse, $errorResponse);
        }

        // 3. Find Customer Invoice across all tenants
        $invoice = Invoice::withoutGlobalScopes()
            ->with(['tenant', 'customer'])
            ->where('invoice_number', $orderId)
            ->first();

        if (!$invoice) {
            $webhookLog->update(['status' => 'FAILED', 'error_message' => 'Invoice atau Order nomor ' . $orderId . ' tidak ditemukan.']);
            return $errorResponse('01', 'Invoice not found');
        }

        // 3. Find tenant's provider configuration
        $paymentMethod = PaymentMethod::withoutGlobalScopes()
            ->where('tenant_id', $invoice->tenant_id)
            ->where('type', 'GATEWAY')
            ->where('provider', $provider)
            ->first();

        if (!$paymentMethod) {
            $webhookLog->update(['status' => 'FAILED', 'error_message' => "Payment method {$provider} untuk tenant {$invoice->tenant_id} tidak aktif."]);
            return $errorResponse('01', 'Payment method not configured');
        }

        // 4. Verify & parse webhook
        $webhookResult = $driver->handleWebhook($request, $paymentMethod);

        if (!$webhookResult->isValid) {
            $errorMsg = $webhookResult->message ?? $webhookResult->errorMessage ?? 'Invalid signature';
            $webhookLog->update(['status' => 'FAILED', 'error_message' => $errorMsg]);
            return $errorResponse('02', $errorMsg);
        }

        // 5. Idempotency Check: if invoice is already PAID, return success immediately
        if ($invoice->isPaid()) {
            $webhookLog->update(['status' => 'IGNORED', 'error_message' => 'Invoice sudah lunas sebelumnya.']);
            return $successResponse($webhookResult);
        }

        // 6. Process successful payment inside DB transaction
        if ($webhookResult->status === 'PAID' || $webhookResult->status === 'SUCCESS') {
            DB::transaction(function () use ($invoice, $paymentMethod, $provider, $webhookResult, $request, $webhookLog) {
                $payment = Payment::create([
                    'tenant_id' => $invoice->tenant_id,
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'payment_method_id' => $paymentMethod->id,
                    'payment_code' => 'PAY-' . $provider . '-' . ($webhookResult->providerReference ?: strtoupper(uniqid())),
                    'amount' => $webhookResult->amount ?: $invoice->total_amount,
                    'status' => 'PENDING',
                    'notes' => "Pembayaran otomatis diverifikasi via Webhook {$provider}.",
                ]);

                PaymentTransaction::create([
                    'tenant_id' => $invoice->tenant_id,
                    'payment_id' => $payment->id,
                    'provider' => $provider,
                    'provider_reference' => $webhookResult->providerReference,
                    'amount' => $webhookResult->amount ?: $invoice->total_amount,
                    'fee' => (float) ($request->input('fee') ?? 0),
                    'payment_channel' => $webhookResult->channel ?? $provider,
                    'status' => 'SUCCESS',
                    'request_payload' => $request->all(),
                ]);

                $this->billingService->markInvoicePaid($invoice, $payment);
                $webhookLog->update(['status' => 'PROCESSED']);
            });

            return $successResponse($webhookResult);
        }

        $webhookLog->update(['status' => 'IGNORED', 'error_message' => 'Status bukan sukses: ' . $webhookResult->status]);
        return $successResponse($webhookResult);
    }

    protected function handleSubscriptionPayment(
        string $provider,
        Request $request,
        Subscription $subscription,
        WebhookLog $webhookLog,
        callable $successResponse,
        callable $errorResponse
    ) {
        $platformGateways = PlatformSetting::get('platform_gateways', []);
        $cfg = $platformGateways[$provider] ?? [];

        if (empty($cfg['merchant_code']) || empty($cfg['api_key'])) {
            $webhookLog->update(['status' => 'FAILED', 'error_message' => "Platform gateway {$provider} belum dikonfigurasi di Pengaturan Super Admin."]);
            return $errorResponse('01', 'Platform gateway unconfigured');
        }

        // Signature Verification for Platform Duitku
        if ($provider === 'DUITKU') {
            $merchantCode = $cfg['merchant_code'];
            $apiKey = $cfg['api_key'];
            $amount = $request->input('amount');
            $merchantOrderId = $request->input('merchantOrderId');
            $signature = $request->input('signature');
            $resultCode = $request->input('resultCode');

            $expectedSig = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
            if ($signature !== $expectedSig) {
                $webhookLog->update(['status' => 'FAILED', 'error_message' => 'Tanda tangan Duitku platform tidak valid.']);
                return $errorResponse('02', 'Bad signature');
            }

            if ($resultCode !== '00') {
                $webhookLog->update(['status' => 'FAILED', 'error_message' => "Pembayaran Duitku gagal dengan resultCode {$resultCode}."]);
                return $successResponse(null);
            }
        }

        // Idempotency: jika sudah ACTIVE, respon sukses langsung
        if ($subscription->status === 'ACTIVE') {
            $webhookLog->update(['status' => 'IGNORED', 'error_message' => 'Langganan sudah lunas dan aktif sebelumnya.']);
            return $successResponse(null);
        }

        // Process activation inside DB transaction
        DB::transaction(function () use ($subscription, $provider, $webhookLog) {
            $tenant = $subscription->tenant;
            $duration = $subscription->billing_cycle === 'yearly' ? 12 : 1;

            $subscription->update([
                'status' => 'ACTIVE',
                'paid_at' => now(),
                'payment_method' => $provider,
                'starts_at' => now(),
                'ends_at' => now()->addMonths($duration),
            ]);

            if ($tenant) {
                $tenant->update([
                    'saas_plan_id' => $subscription->saas_plan_id,
                    'status' => 'ACTIVE',
                    'trial_ends_at' => null,
                ]);
            }

            AuditLog::create([
                'tenant_id' => $tenant?->id,
                'user_id' => null,
                'event' => 'SUBSCRIPTION_PAID_ONLINE',
                'description' => "Langganan paket {$subscription->saasPlan?->name} ({$subscription->order_number}) otomatis aktif via gateway platform {$provider}.",
            ]);

            $webhookLog->update(['status' => 'PROCESSED']);
        });

        return $successResponse(null);
    }
}
