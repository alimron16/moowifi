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

        SaasPlan::create($validated);

        return back()->with('success', 'Paket SaaS baru berhasil dibuat.');
    }
}
