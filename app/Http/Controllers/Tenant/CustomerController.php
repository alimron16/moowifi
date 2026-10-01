<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Router;
use App\Services\BillingService;
use App\Services\MikrotikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct(
        protected MikrotikService $mikrotikService,
        protected BillingService $billingService
    ) {}

    public function index(Request $request)
    {
        $query = Customer::with(['package', 'router'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('customer_code', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('mikrotik_username', 'like', "%{$s}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();
        $packages = Package::where('status', 'ACTIVE')->get();

        return view('tenant.customers.index', compact('customers', 'packages'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $packages = Package::where('status', 'ACTIVE')->get();
        $routers = Router::all();

        // Generate next customer code e.g. CUST-0001
        $count = Customer::count() + 1;
        $suggestedCode = sprintf('CUST-%04d', $count);

        return view('tenant.customers.create', compact('packages', 'routers', 'suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'package_id' => ['required', 'exists:packages,id'],
            'router_id' => ['nullable', 'exists:routers,id'],
            'connection_type' => ['required', 'in:PPPOE,HOTSPOT,STATIC_IP'],
            'mikrotik_username' => ['nullable', 'string', 'max:50'],
            'mikrotik_password' => ['nullable', 'string'],
            'billing_type' => ['required', 'in:PREPAID,POSTPAID'],
            'billing_day' => ['required', 'integer', 'min:1', 'max:28'],
            'due_day' => ['required', 'integer', 'min:1', 'max:28'],
            'grace_period_days' => ['required', 'integer', 'min:0', 'max:30'],
            'auto_cut_enabled' => ['nullable', 'boolean'],
            'sync_to_mikrotik' => ['nullable', 'boolean'],
        ]);

        $validated['auto_cut_enabled'] = $request->boolean('auto_cut_enabled', true);
        if (!empty($validated['mikrotik_password'])) {
            $validated['encrypted_mikrotik_password'] = $validated['mikrotik_password'];
            unset($validated['mikrotik_password']);
        }

        $customer = Customer::create($validated);

        // Sync secret to MikroTik if requested and router is selected
        if ($request->boolean('sync_to_mikrotik') && $customer->router) {
            $this->mikrotikService->syncPppoeSecret($customer->router, $customer, $customer->package);
        }

        AuditLog::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => Customer::class,
            'auditable_id' => $customer->id,
            'event' => 'CUSTOMER_CREATED',
            'description' => "Menambahkan pelanggan baru: {$customer->name} ({$customer->customer_code}).",
        ]);

        return redirect()->route('tenant.customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        $packages = Package::where('status', 'ACTIVE')->get();
        $routers = Router::all();

        return view('tenant.customers.edit', compact('customer', 'packages', 'routers'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'package_id' => ['required', 'exists:packages,id'],
            'router_id' => ['nullable', 'exists:routers,id'],
            'connection_type' => ['required', 'in:PPPOE,HOTSPOT,STATIC_IP'],
            'mikrotik_username' => ['nullable', 'string', 'max:50'],
            'mikrotik_password' => ['nullable', 'string'],
            'billing_type' => ['required', 'in:PREPAID,POSTPAID'],
            'billing_day' => ['required', 'integer', 'min:1', 'max:28'],
            'due_day' => ['required', 'integer', 'min:1', 'max:28'],
            'grace_period_days' => ['required', 'integer', 'min:0', 'max:30'],
            'status' => ['required', 'in:ACTIVE,UNPAID,OVERDUE,ISOLATED,SUSPENDED,INACTIVE'],
            'auto_cut_enabled' => ['nullable', 'boolean'],
            'sync_to_mikrotik' => ['nullable', 'boolean'],
        ]);

        $validated['auto_cut_enabled'] = $request->boolean('auto_cut_enabled');
        if (!empty($validated['mikrotik_password'])) {
            $validated['encrypted_mikrotik_password'] = $validated['mikrotik_password'];
            unset($validated['mikrotik_password']);
        }

        $customer->update($validated);

        if ($request->boolean('sync_to_mikrotik') && $customer->router) {
            $this->mikrotikService->syncPppoeSecret($customer->router, $customer, $customer->package);
        }

        return redirect()->route('tenant.customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();

        return redirect()->route('tenant.customers.index')->with('success', "Pelanggan {$name} berhasil dihapus.");
    }

    public function forceIsolate(Customer $customer)
    {
        $this->billingService->isolateCustomer($customer);
        return back()->with('success', "Pelanggan {$customer->name} telah berhasil diisolir.");
    }

    public function forceRestore(Customer $customer)
    {
        if ($customer->router) {
            $this->mikrotikService->restoreCustomer($customer->router, $customer, $customer->package);
        }

        $customer->update(['status' => 'ACTIVE']);
        return back()->with('success', "Akses internet pelanggan {$customer->name} berhasil diaktifkan kembali.");
    }
}
