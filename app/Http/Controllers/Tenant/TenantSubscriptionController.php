<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PlatformSetting;
use App\Models\SaasPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TenantSubscriptionController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $activePlan = $tenant?->activePlan();
        $currentSubscription = $tenant?->currentSubscription;

        $plans = SaasPlan::where('is_active', true)->orderBy('price')->get();
        $isExpired = $tenant?->isExpired() ?? false;
        $isTrial = $tenant?->isTrial() ?? false;

        $trialDaysLeft = ($tenant && $tenant->trial_ends_at)
            ? max(0, (int) now()->diffInDays($tenant->trial_ends_at, false))
            : 0;

        $subscriptionDaysLeft = ($currentSubscription && $currentSubscription->ends_at)
            ? max(0, (int) now()->diffInDays($currentSubscription->ends_at, false))
            : 0;

        $currentPrice = $activePlan ? (float) $activePlan->price : 0;

        // Cek jika ada pesanan pending / menunggu verifikasi
        $pendingOrder = Subscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['PENDING', 'WAITING_VERIFICATION'])
            ->latest()
            ->first();

        return view('tenant.subscription.index', compact(
            'tenant',
            'activePlan',
            'currentSubscription',
            'plans',
            'isExpired',
            'isTrial',
            'trialDaysLeft',
            'subscriptionDaysLeft',
            'currentPrice',
            'pendingOrder'
        ));
    }

    public function upgrade(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'saas_plan_id' => ['required', 'exists:saas_plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
        ], [
            'saas_plan_id.required' => 'Silakan pilih paket langganan yang ingin diaktifkan.',
            'billing_cycle.required' => 'Pilih siklus penagihan bulanan atau tahunan.',
        ]);

        $targetPlan = SaasPlan::findOrFail($validated['saas_plan_id']);
        $currentPlan = $tenant->activePlan();

        $currentPrice = $currentPlan ? (float) $currentPlan->price : 0;
        $targetPrice = (float) $targetPlan->price;

        // Aturan Kritis: Downgrade Mandiri Ditolak! Harus melalui Super Admin
        if ($currentPlan && $targetPrice < $currentPrice) {
            return back()->with('error', 'Penurunan paket (downgrade) ke kuota yang lebih rendah tidak dapat dilakukan secara mandiri untuk mencegah terputusnya data pelanggan Anda yang telah melebihi batas kuota. Silakan hubungi Super Admin untuk penyesuaian paket khusus.');
        }

        $isYearly = $validated['billing_cycle'] === 'yearly';
        $amount = $isYearly ? $targetPlan->getYearlyPriceAttribute() : (float) $targetPlan->price;
        $orderNumber = 'ORD-SUB-' . date('Ym') . '-' . rand(1000, 9999);

        // Buat order subscription berstatus PENDING (Menunggu Pembayaran Transfer)
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'saas_plan_id' => $targetPlan->id,
            'order_number' => $orderNumber,
            'amount' => $amount,
            'billing_cycle' => $validated['billing_cycle'],
            'status' => 'PENDING',
        ]);

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'event' => 'SUBSCRIPTION_ORDER_CREATED',
            'description' => "Tenant membuat pesanan langganan {$targetPlan->name} ({$orderNumber}) sebesar Rp " . number_format($amount, 0, ',', '.'),
        ]);

        return redirect()->route('tenant.subscription.payment', $subscription->id)
            ->with('success', 'Pesanan paket langganan berhasil dibuat. Silakan lakukan pembayaran transfer sesuai rincian rekening di bawah.');
    }

    public function payment(Subscription $subscription)
    {
        $tenant = Auth::user()->tenant;

        if ($subscription->tenant_id !== $tenant->id) {
            abort(403, 'Akses pesanan langganan ditolak.');
        }

        // Ambil rekening bank & instruksi pembayaran yang telah diatur oleh Super Admin di platform
        $manualBanks = PlatformSetting::get('platform_manual_banks', [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT MooWiFi Digital Indonesia',
                'instructions' => 'Transfer tepat sesuai nominal tagihan dan simpan bukti transfer.',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '1370012345678',
                'account_name' => 'PT MooWiFi Digital Indonesia',
                'instructions' => 'Transfer via ATM / m-Banking / Internet Banking Mandiri.',
                'is_active' => true,
            ]
        ]);

        $platformProfile = PlatformSetting::get('platform_profile', [
            'contact_phone' => '088976291662',
            'contact_email' => 'support@moowifi.id',
        ]);

        $platformGateways = PlatformSetting::get('platform_gateways', []);

        $activeGateway = null;
        $activeGatewayConfig = [];
        foreach (['DUITKU', 'MIDTRANS', 'XENDIT', 'TRIPAY'] as $gw) {
            $cfg = $platformGateways[$gw] ?? [];
            if (!empty($cfg['is_active'])) {
                $isConfigured = match ($gw) {
                    'DUITKU' => !empty($cfg['merchant_code']) && !empty($cfg['api_key']),
                    'MIDTRANS' => !empty($cfg['server_key']),
                    'XENDIT' => !empty($cfg['secret_key']),
                    'TRIPAY' => !empty($cfg['private_key']) && !empty($cfg['api_key']),
                    default => false,
                };
                if ($isConfigured) {
                    $activeGateway = $gw;
                    $activeGatewayConfig = $cfg;
                    break;
                }
            }
        }

        $duitkuActive = !is_null($activeGateway);
        $onlinePaymentActive = !is_null($activeGateway);

        return view('tenant.subscription.payment', compact(
            'tenant',
            'subscription',
            'manualBanks',
            'platformProfile',
            'duitkuActive',
            'onlinePaymentActive',
            'activeGateway',
            'activeGatewayConfig'
        ));
    }

    public function payDuitku(Request $request, Subscription $subscription)
    {
        $tenant = Auth::user()->tenant;

        if ($subscription->tenant_id !== $tenant->id) {
            abort(403, 'Akses pesanan langganan ditolak.');
        }

        if ($subscription->status === 'ACTIVE') {
            return redirect()->route('tenant.subscription.index')
                ->with('success', 'Paket langganan ini sudah lunas dan aktif.');
        }

        $platformGateways = PlatformSetting::get('platform_gateways', []);

        $activeGateway = null;
        $cfg = [];
        foreach (['DUITKU', 'MIDTRANS', 'XENDIT', 'TRIPAY'] as $gw) {
            $c = $platformGateways[$gw] ?? [];
            if (!empty($c['is_active'])) {
                $isConfigured = match ($gw) {
                    'DUITKU' => !empty($c['merchant_code']) && !empty($c['api_key']),
                    'MIDTRANS' => !empty($c['server_key']),
                    'XENDIT' => !empty($c['secret_key']),
                    'TRIPAY' => !empty($c['private_key']) && !empty($c['api_key']),
                    default => false,
                };
                if ($isConfigured) {
                    $activeGateway = $gw;
                    $cfg = $c;
                    break;
                }
            }
        }

        if (!$activeGateway) {
            return back()->with('error', 'Pembayaran online untuk platform SaaS belum diaktifkan oleh Admin di menu Pengaturan Platform.');
        }

        $amount = (int) round($subscription->amount);
        $merchantOrderId = $subscription->order_number;
        $isSandbox = ($cfg['environment'] ?? 'sandbox') === 'sandbox';

        // 1. MIDTRANS SNAP
        if ($activeGateway === 'MIDTRANS') {
            $endpoint = $isSandbox
                ? 'https://app.sandbox.midtrans.com/snap/v1/transactions'
                : 'https://app.midtrans.com/snap/v1/transactions';

            $payload = [
                'transaction_details' => [
                    'order_id' => $merchantOrderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => substr(Auth::user()->name ?: $tenant->name, 0, 40),
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '088976291662',
                ],
                'item_details' => [
                    [
                        'id' => 'SUB-' . $subscription->id,
                        'price' => $amount,
                        'quantity' => 1,
                        'name' => 'Langganan ' . ($subscription->saasPlan?->name ?? 'Pro'),
                    ],
                ],
                'callbacks' => [
                    'finish' => route('tenant.subscription.payment', $subscription->id),
                ],
            ];

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode(trim($cfg['server_key']) . ':'),
                ])->timeout(15)->post($endpoint, $payload);

                $data = $response->json();
                if ($response->successful() && !empty($data['redirect_url'])) {
                    $subscription->update(['payment_method' => 'MIDTRANS']);
                    return redirect()->away($data['redirect_url']);
                }

                $msg = $data['error_messages'][0] ?? ($response->body() ?: 'Gagal membuat tagihan Midtrans.');
                return back()->with('error', 'Midtrans Response: ' . $msg);
            } catch (\Throwable $e) {
                return back()->with('error', 'Koneksi Midtrans gagal: ' . $e->getMessage());
            }
        }

        // 2. XENDIT INVOICE
        if ($activeGateway === 'XENDIT') {
            $endpoint = 'https://api.xendit.co/v2/invoices';
            $payload = [
                'external_id' => $merchantOrderId,
                'amount' => $amount,
                'payer_email' => Auth::user()->email,
                'description' => 'Langganan MooWiFi Paket ' . ($subscription->saasPlan?->name ?? 'Pro') . ' (' . $merchantOrderId . ')',
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => substr(Auth::user()->name ?: $tenant->name, 0, 40),
                    'email' => Auth::user()->email,
                    'mobile_number' => Auth::user()->phone ?? '088976291662',
                ],
                'success_redirect_url' => route('tenant.subscription.payment', $subscription->id),
                'failure_redirect_url' => route('tenant.subscription.payment', $subscription->id),
            ];

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode(trim($cfg['secret_key']) . ':'),
                ])->timeout(15)->post($endpoint, $payload);

                $data = $response->json();
                if ($response->successful() && !empty($data['invoice_url'])) {
                    $subscription->update(['payment_method' => 'XENDIT']);
                    return redirect()->away($data['invoice_url']);
                }

                $msg = $data['message'] ?? ($response->body() ?: 'Gagal membuat invoice Xendit.');
                return back()->with('error', 'Xendit Response: ' . $msg);
            } catch (\Throwable $e) {
                return back()->with('error', 'Koneksi Xendit gagal: ' . $e->getMessage());
            }
        }

        // 3. TRIPAY TRANSACTION
        if ($activeGateway === 'TRIPAY') {
            $endpoint = $isSandbox
                ? 'https://tripay.co.id/api-sandbox/transaction/create'
                : 'https://tripay.co.id/api/transaction/create';

            $merchantCode = trim($cfg['merchant_code']);
            $privateKey = trim($cfg['private_key']);
            $apiKey = trim($cfg['api_key']);
            $signature = hash_hmac('sha256', $merchantCode . $merchantOrderId . $amount, $privateKey);

            $payload = [
                'method' => 'QRIS2',
                'merchant_ref' => $merchantOrderId,
                'amount' => $amount,
                'customer_name' => substr(Auth::user()->name ?: $tenant->name, 0, 40),
                'customer_email' => Auth::user()->email,
                'customer_phone' => Auth::user()->phone ?? '088976291662',
                'order_items' => [
                    [
                        'name' => 'Langganan ' . ($subscription->saasPlan?->name ?? 'Pro'),
                        'price' => $amount,
                        'quantity' => 1,
                    ],
                ],
                'callback_url' => url('/api/webhooks/tripay'),
                'return_url' => route('tenant.subscription.payment', $subscription->id),
                'expired_time' => time() + 86400,
                'signature' => $signature,
            ];

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                ])->timeout(15)->post($endpoint, $payload);

                $data = $response->json();
                if ($response->successful() && !empty($data['data']['checkout_url'])) {
                    $subscription->update(['payment_method' => 'TRIPAY']);
                    return redirect()->away($data['data']['checkout_url']);
                }

                $msg = $data['message'] ?? ($response->body() ?: 'Gagal membuat tagihan Tripay.');
                return back()->with('error', 'Tripay Response: ' . $msg);
            } catch (\Throwable $e) {
                return back()->with('error', 'Koneksi Tripay gagal: ' . $e->getMessage());
            }
        }

        // 4. DUITKU (Default / Fallback)
        $merchantCode = trim($cfg['merchant_code']);
        $apiKey = trim($cfg['api_key']);
        $channel = trim((string) $request->input('payment_channel', ''));

        // Coba Duitku POP jika channel kosong
        if (empty($channel)) {
            $timestamp = (string) round(microtime(true) * 1000);
            $signaturePop = hash_hmac('sha256', $merchantCode . $timestamp, $apiKey);

            $popEndpoint = $isSandbox
                ? 'https://api-sandbox.duitku.com/api/merchant/createInvoice'
                : 'https://api-prod.duitku.com/api/merchant/createInvoice';

            $popPayload = [
                'paymentAmount' => $amount,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => 'Langganan MooWiFi Paket ' . ($subscription->saasPlan?->name ?? 'Pro') . ' (' . $merchantOrderId . ')',
                'customerVaName' => substr(Auth::user()->name ?: $tenant->name, 0, 30),
                'email' => Auth::user()->email,
                'phoneNumber' => Auth::user()->phone ?? '088976291662',
                'callbackUrl' => url('/api/webhooks/duitku'),
                'returnUrl' => route('tenant.subscription.payment', $subscription->id),
                'expiryPeriod' => 1440,
            ];

            try {
                $popResponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-duitku-signature' => $signaturePop,
                    'x-duitku-timestamp' => $timestamp,
                    'x-duitku-merchantcode' => $merchantCode,
                ])->timeout(15)->post($popEndpoint, $popPayload);

                $popData = $popResponse->json();

                if ($popResponse->successful() && !empty($popData['paymentUrl'])) {
                    $subscription->update(['payment_method' => 'DUITKU']);
                    return redirect()->away($popData['paymentUrl']);
                }
            } catch (\Throwable $e) {
                Log::warning('Duitku POP createInvoice warning: ' . $e->getMessage());
            }

            // Fallback default ke QRIS jika direct API
            $channel = 'NQ';
        }

        // Direct API Inquiry (v2)
        $endpoint = $isSandbox
            ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'
            : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';

        $signature = md5($merchantCode . $merchantOrderId . $amount . $apiKey);

        $payload = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => $channel,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Langganan MooWiFi Paket ' . ($subscription->saasPlan?->name ?? 'Pro') . ' (' . $merchantOrderId . ')',
            'email' => Auth::user()->email,
            'phoneNumber' => Auth::user()->phone ?? '088976291662',
            'customerVaName' => substr(Auth::user()->name ?: $tenant->name, 0, 30),
            'callbackUrl' => url('/api/webhooks/duitku'),
            'returnUrl' => route('tenant.subscription.payment', $subscription->id),
            'signature' => $signature,
            'expiryPeriod' => 1440,
        ];

        try {
            $response = Http::timeout(15)->post($endpoint, $payload);
            $data = $response->json();

            if ($response->successful() && isset($data['statusCode']) && $data['statusCode'] === '00' && !empty($data['paymentUrl'])) {
                $subscription->update(['payment_method' => 'DUITKU']);
                return redirect()->away($data['paymentUrl']);
            }

            $errorMessage = $data['statusMessage'] 
                ?? $data['Message'] 
                ?? $data['message'] 
                ?? ($response->body() ?: 'Gagal membuat tagihan di Duitku. Pastikan Merchant Code dan API Key Duitku Platform sudah sesuai.');

            return back()->with('error', 'Duitku Response: ' . $errorMessage);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghubungi server Duitku: ' . $e->getMessage());
        }
    }

    public function confirmPayment(Request $request, Subscription $subscription)
    {
        $tenant = Auth::user()->tenant;

        if ($subscription->tenant_id !== $tenant->id) {
            abort(403, 'Akses pesanan langganan ditolak.');
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'max:150'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'payment_method.required' => 'Pilih bank tujuan transfer yang Anda gunakan.',
            'proof.required' => 'Unggah foto struk atau tangkapan layar bukti transfer.',
            'proof.mimes' => 'Format file bukti transfer harus berupa JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran file bukti transfer maksimal 5 MB.',
        ]);

        try {
            // Pastikan folder penyimpanan bukti transfer tersedia
            if (!Storage::disk('public')->exists('subscription_proofs')) {
                Storage::disk('public')->makeDirectory('subscription_proofs');
            }

            $proofPath = $request->file('proof')->store('subscription_proofs', 'public');

            $subscription->update([
                'payment_method' => $request->payment_method,
                'proof_path' => $proofPath,
                'status' => 'WAITING_VERIFICATION',
            ]);

            AuditLog::create([
                'tenant_id' => $tenant->id,
                'user_id' => Auth::id(),
                'event' => 'SUBSCRIPTION_PAYMENT_SUBMITTED',
                'description' => "Tenant mengunggah bukti transfer untuk order {$subscription->order_number} via {$request->payment_method}.",
            ]);

            return back()->with('success', 'Bukti pembayaran transfer berhasil diunggah! Tim Super Admin akan segera memverifikasi pembayaran Anda dalam 5-15 menit untuk mengaktifkan paket secara penuh.');
        } catch (\Throwable $e) {
            Log::error("Gagal verifikasi pembayaran langganan SaaS: {$e->getMessage()}", [
                'subscription_id' => $subscription->id,
                'exception' => $e,
            ]);

            return back()->with('error', 'Gagal memproses unggahan bukti transfer: ' . $e->getMessage() . '. Pastikan izin folder storage di server telah diatur.');
        }
    }
}
