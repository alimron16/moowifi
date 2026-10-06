<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PlatformSetting;
use App\Models\SaasPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'contact_phone' => '081122334455',
            'contact_email' => 'support@moowifi.id',
        ]);

        return view('tenant.subscription.payment', compact(
            'tenant',
            'subscription',
            'manualBanks',
            'platformProfile'
        ));
    }

    public function confirmPayment(Request $request, Subscription $subscription)
    {
        $tenant = Auth::user()->tenant;

        if ($subscription->tenant_id !== $tenant->id) {
            abort(403, 'Akses pesanan langganan ditolak.');
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'max:50'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
        ], [
            'payment_method.required' => 'Pilih bank tujuan transfer yang Anda gunakan.',
            'proof.required' => 'Unggah foto struk atau tangkapan layar bukti transfer.',
            'proof.mimes' => 'Format file bukti transfer harus berupa JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran file bukti transfer maksimal 3 MB.',
        ]);

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
    }
}
