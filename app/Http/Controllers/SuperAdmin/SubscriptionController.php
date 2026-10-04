<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SaasPlan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['tenant', 'saasPlan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->paginate(15)->withQueryString();
        $plans = SaasPlan::where('is_active', true)->get();
        $tenants = Tenant::all();

        return view('super-admin.subscriptions.index', compact('subscriptions', 'plans', 'tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'exists:tenants,id'],
            'saas_plan_id' => ['required', 'exists:saas_plans,id'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:24'],
        ]);

        $startsAt = now();
        $endsAt = now()->addMonths((int)$validated['duration_months']);

        $subscription = Subscription::create([
            'tenant_id' => $validated['tenant_id'],
            'saas_plan_id' => $validated['saas_plan_id'],
            'status' => 'ACTIVE',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        Tenant::where('id', $validated['tenant_id'])->update(['status' => 'ACTIVE']);

        AuditLog::create([
            'event' => 'SUBSCRIPTION_CREATED',
            'description' => "Super Admin mengaktifkan paket langganan untuk tenant ID #{$validated['tenant_id']} hingga {$endsAt->format('d/m/Y')}.",
        ]);

        return back()->with('success', 'Paket langganan tenant berhasil diaktifkan/diperpanjang.');
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'saas_plan_id' => ['required', 'exists:saas_plans,id'],
            'status' => ['required', 'in:ACTIVE,TRIAL,EXPIRED,CANCELLED'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
        ]);

        $subscription->update($validated);

        // Sync tenant status
        if ($validated['status'] === 'ACTIVE') {
            $subscription->tenant?->update(['status' => 'ACTIVE']);
        } elseif ($validated['status'] === 'EXPIRED') {
            $subscription->tenant?->update(['status' => 'EXPIRED']);
        }

        AuditLog::create([
            'event' => 'SUBSCRIPTION_UPDATED',
            'description' => "Super Admin memperbarui langganan #{$subscription->id} tenant {$subscription->tenant?->name} menjadi status {$validated['status']}.",
        ]);

        return back()->with('success', "Langganan tenant {$subscription->tenant?->name} berhasil diperbarui.");
    }

    public function destroy(Subscription $subscription)
    {
        $tenantName = $subscription->tenant?->name ?? 'Tenant';
        $subscription->delete();

        AuditLog::create([
            'event' => 'SUBSCRIPTION_DELETED',
            'description' => "Super Admin menghapus langganan #{$subscription->id} tenant {$tenantName}.",
        ]);

        return back()->with('success', "Data langganan berhasil dihapus.");
    }

    public function payments(Request $request)
    {
        // SaaS platform subscription payments
        $subscriptions = Subscription::with(['tenant', 'saasPlan'])
            ->where('status', 'ACTIVE')
            ->latest()
            ->paginate(15);

        return view('super-admin.payments.index', compact('subscriptions'));
    }
}
