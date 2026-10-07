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
        $duitku = $platformGateways['DUITKU'] ?? [];
        $duitkuActive = !empty($duitku['is_active'])
            && !empty($duitku['merchant_code'])
            && !empty($duitku['api_key']);

        return view('tenant.subscription.payment', compact(
            'tenant',
            'subscription',
            'manualBanks',
            'platformProfile',
            'duitkuActive',
            'duitku'
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
        $duitku = $platformGateways['DUITKU'] ?? [];

        if (empty($duitku['is_active']) || empty($duitku['merchant_code']) || empty($duitku['api_key'])) {
            return back()->with('error', 'Pembayaran online Duitku untuk platform SaaS belum diaktifkan atau belum diisi oleh Admin di menu Pengaturan Platform.');
        }

        $merchantCode = trim($duitku['merchant_code']);
        $apiKey = trim($duitku['api_key']);
        $isSandbox = ($duitku['environment'] ?? 'sandbox') === 'sandbox';

        $merchantOrderId = $subscription->order_number;
        $amount = (int) round($subscription->amount);
        $channel = trim((string) $request->input('payment_channel', ''));

        // 1. Jika channel kosong ("Semua Channel"), coba terlebih dahulu Duitku POP createInvoice
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
                    $subscription->update([
                        'payment_method' => 'DUITKU',
                    ]);

                    AuditLog::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => Auth::id(),
                        'event' => 'SUBSCRIPTION_PAYMENT_REDIRECTED',
                        'description' => "Tenant menginisiasi pembayaran online via Duitku POP untuk order {$subscription->order_number}.",
                    ]);

                    return redirect()->away($popData['paymentUrl']);
                }
            } catch (\Throwable $e) {
                Log::warning('Duitku POP createInvoice warning: ' . $e->getMessage());
            }

            // Fallback default ke QRIS jika akun merchant menggunakan Direct API
            $channel = 'NQ';
        }

        // 2. Direct API Inquiry (v2) dengan channel spesifik (NQ untuk QRIS, BC untuk BCA VA, dsb)
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
                $subscription->update([
                    'payment_method' => 'DUITKU',
                ]);

                AuditLog::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => Auth::id(),
                    'event' => 'SUBSCRIPTION_PAYMENT_REDIRECTED',
                    'description' => "Tenant menginisiasi pembayaran online via Duitku ({$channel}) untuk order {$subscription->order_number}.",
                ]);

                return redirect()->away($data['paymentUrl']);
            }

            $errorMessage = $data['statusMessage'] 
                ?? $data['Message'] 
                ?? $data['message'] 
                ?? ($response->body() ?: 'Gagal membuat tagihan di Duitku. Pastikan Merchant Code dan API Key Duitku Platform sudah sesuai.');

            Log::error('Duitku subscription payment failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            return back()->with('error', 'Duitku Response: ' . $errorMessage);
        } catch (\Throwable $e) {
            Log::error('Duitku subscription payment error: ' . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'exception' => $e,
            ]);

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
