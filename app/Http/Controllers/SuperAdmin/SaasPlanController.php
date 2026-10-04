<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use Illuminate\Http\Request;

class SaasPlanController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::withCount('subscriptions')->get();
        return view('super-admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:30', 'unique:saas_plans,code'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_customers' => ['required', 'integer'],
            'max_routers' => ['required', 'integer'],
        ]);

        $validated['is_active'] = true;
        SaasPlan::create($validated);

        return back()->with('success', 'Paket SaaS baru berhasil dibuat.');
    }

    public function update(Request $request, SaasPlan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:30', 'unique:saas_plans,code,' . $plan->id],
            'price' => ['required', 'numeric', 'min:0'],
            'max_customers' => ['required', 'integer'],
            'max_routers' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $plan->update($validated);

        return back()->with('success', "Paket SaaS {$plan->name} berhasil diperbarui.");
    }

    public function destroy(SaasPlan $plan)
    {
        if ($plan->subscriptions()->where('status', 'ACTIVE')->exists()) {
            return back()->with('error', "Paket {$plan->name} tidak dapat dihapus karena masih digunakan oleh tenant aktif.");
        }

        $plan->delete();
        return back()->with('success', "Paket SaaS {$plan->name} berhasil dihapus.");
    }
}
