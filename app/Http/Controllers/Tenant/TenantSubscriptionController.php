<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
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

        return view('tenant.subscription.index', compact(
            'tenant',
            'activePlan',
            'currentSubscription',
            'plans',
            'isExpired',
            'isTrial',
            'trialDaysLeft',
            'subscriptionDaysLeft',
            'currentPrice'
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

        $durationMonths = $validated['billing_cycle'] === 'yearly' ? 12 : 1;
        $startsAt = now();
        $endsAt = now()->addMonths($durationMonths);

        // Buat subscription aktif baru
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'saas_plan_id' => $targetPlan->id,
            'status' => 'ACTIVE',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        $tenant->update([
            'status' => 'ACTIVE',
            'plan' => $targetPlan->name,
        ]);

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'event' => 'SUBSCRIPTION_UPGRADED',
            'description' => "Tenant mengupgrade paket SaaS ke {$targetPlan->name} ({$durationMonths} Bulan) hingga {$endsAt->format('d/m/Y')}.",
        ]);

        $message = "Selamat! Paket Anda berhasil diperbarui ke {$targetPlan->name}. Kuota maksimal {$targetPlan->max_customers} pelanggan dan {$targetPlan->max_routers} router kini aktif hingga {$endsAt->format('d M Y')}.";

        return redirect()->route('tenant.dashboard')->with('success', $message);
    }
}
