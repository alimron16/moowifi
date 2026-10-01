<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['users' => fn($q) => $q->where('role', 'OWNER')])
            ->withCount(['customers' => fn($q) => $q->withoutGlobalScopes(), 'routers' => fn($q) => $q->withoutGlobalScopes()])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $tenants = $query->paginate(15)->withQueryString();

        return view('super-admin.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['users', 'subscriptions', 'subscriptions.saasPlan']);
        $customersCount = \App\Models\Customer::withoutGlobalScopes()->where('tenant_id', $tenant->id)->count();
        $routers = \App\Models\Router::withoutGlobalScopes()->where('tenant_id', $tenant->id)->get();
        $invoicesCount = \App\Models\Invoice::withoutGlobalScopes()->where('tenant_id', $tenant->id)->count();
        $revenue = \App\Models\Payment::withoutGlobalScopes()->where('tenant_id', $tenant->id)->where('status', 'SUCCESS')->sum('amount');

        return view('super-admin.tenants.show', compact('tenant', 'customersCount', 'routers', 'invoicesCount', 'revenue'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:10', 'unique:tenants,code'],
            'owner_name' => ['required', 'string', 'max:100'],
            'owner_email' => ['required', 'email', 'unique:users,email'],
            'owner_phone' => ['required', 'string', 'max:30'],
            'owner_password' => ['required', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($request) {
            $slug = Str::slug($request->name);
            $tenant = Tenant::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'slug' => $slug . '-' . Str::random(4),
                'phone' => $request->owner_phone,
                'email' => $request->owner_email,
                'status' => 'TRIAL',
                'trial_ends_at' => now()->addDays(14),
            ]);

            User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'phone' => $request->owner_phone,
                'role' => 'OWNER',
                'password' => Hash::make($request->owner_password),
                'status' => 'ACTIVE',
            ]);

            AuditLog::create([
                'event' => 'TENANT_CREATED',
                'description' => "Super Admin mendaftarkan tenant baru: {$tenant->name} ({$tenant->code}).",
            ]);
        });

        return back()->with('success', 'Tenant RT/RW Net baru dan akun Owner berhasil dibuat.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $newStatus = $tenant->status === 'ACTIVE' ? 'SUSPENDED' : 'ACTIVE';
        $tenant->update(['status' => $newStatus]);

        return back()->with('success', "Status tenant {$tenant->name} diubah menjadi {$newStatus}.");
    }
}
