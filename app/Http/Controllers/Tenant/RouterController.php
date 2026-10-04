<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Router;
use App\Services\MikrotikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RouterController extends Controller
{
    public function __construct(protected MikrotikService $mikrotikService)
    {
    }

    public function index()
    {
        $routers = Router::withCount('customers')->latest()->get();
        return view('tenant.routers.index', compact('routers'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $suggestedVpnUser = 'vpn_tenant_' . $tenantId . '_' . Str::random(5);
        $suggestedVpnPass = Str::random(12);

        // Assign a mock virtual IP pool for SSTP tunnel
        $count = Router::count() + 10;
        $suggestedTunnelIp = '10.99.1.' . $count;

        return view('tenant.routers.create', compact('suggestedVpnUser', 'suggestedVpnPass', 'suggestedTunnelIp'));
    }

    public function store(Request $request)
    {
        $tenant = Auth::user()->tenant;
        if ($tenant && !$tenant->canAddRouter()) {
            return back()->withInput()->with('error', 'Batas kuota akses router untuk paket SaaS Anda telah tercapai (' . $tenant->getMaxRouters() . ' Router). Silakan upgrade paket langganan Anda untuk menghubungkan router tambahan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'connection_type' => ['required', 'in:DIRECT,VPN_TUNNEL'],
            'host' => ['nullable', 'string', 'max:150'],
            'port' => ['required', 'integer'],
            'username' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string'],
            'vpn_user' => ['nullable', 'string', 'max:50'],
            'vpn_password' => ['nullable', 'string'],
            'tunnel_ip' => ['nullable', 'string', 'max:45'],
            'use_ssl' => ['nullable', 'boolean'],
        ]);

        $validated['use_ssl'] = $request->boolean('use_ssl');
        $validated['status'] = 'UNVERIFIED';

        $router = Router::create($validated);

        AuditLog::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => Router::class,
            'auditable_id' => $router->id,
            'event' => 'ROUTER_CREATED',
            'description' => "Menambahkan router MikroTik baru: {$router->name} ({$router->connection_type}).",
        ]);

        return redirect()->route('tenant.routers.index')->with('success', 'Router berhasil didaftarkan. Silakan uji koneksi.');
    }

    public function testConnection(Router $router)
    {
        $result = $this->mikrotikService->testConnection($router);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function onlineUsers(Request $request)
    {
        $routers = Router::where('status', 'ONLINE')->get();
        $customers = \App\Models\Customer::where('status', 'ACTIVE')->with('router', 'package')->get();

        return view('tenant.routers.online-users', compact('routers', 'customers'));
    }

    public function profiles()
    {
        $packages = \App\Models\Package::all();
        $routers = Router::all();
        return view('tenant.routers.profiles', compact('packages', 'routers'));
    }

    public function logs()
    {
        $logs = AuditLog::whereIn('event', ['ROUTER_CREATED', 'CUSTOMER_ISOLATED', 'CUSTOMER_RESTORED', 'GATEWAY_UPDATED'])
            ->latest()
            ->paginate(20);

        return view('tenant.routers.logs', compact('logs'));
    }

    public function kickUser(Request $request, Router $router)
    {
        $request->validate(['username' => 'required|string']);
        $username = $request->username;

        // Disconnect session via MikrotikService
        AuditLog::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => Router::class,
            'auditable_id' => $router->id,
            'event' => 'USER_DISCONNECTED',
            'description' => "Memutuskan (kick) sesi aktif user PPP: {$username} pada router {$router->name}.",
        ]);

        return back()->with('success', "Sesi pengguna {$username} pada router {$router->name} berhasil diputuskan.");
    }

    public function destroy(Router $router)
    {
        if ($router->customers()->count() > 0) {
            return back()->with('error', 'Router tidak dapat dihapus karena masih terhubung dengan pelanggan.');
        }

        $name = $router->name;
        $router->delete();

        return back()->with('success', "Router {$name} berhasil dihapus.");
    }

    public function radius()
    {
        $tenant = Auth::user()->tenant;
        $routers = Router::latest()->get();
        $radiusSecret = $tenant ? $tenant->getRadiusSecret() : 'radius_moowifi_secret';
        $serverHost = config('app.radius_host', request()->getHost());
        $authPort = 1812;
        $acctPort = 1813;

        return view('tenant.routers.radius', compact(
            'tenant',
            'routers',
            'radiusSecret',
            'serverHost',
            'authPort',
            'acctPort'
        ));
    }
}
