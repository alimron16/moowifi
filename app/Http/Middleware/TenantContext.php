<?php

namespace App\Http\Middleware;

use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantContext
{
    public function __construct(protected TenantManager $tenantManager)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->tenant_id && !$user->isSuperAdmin()) {
                $tenant = $user->tenant;

                if ($tenant) {
                    if ($tenant->status === 'SUSPENDED') {
                        Auth::logout();
                        return redirect()->route('login')->withErrors([
                            'email' => 'Akun tenant Anda sedang ditangguhkan. Hubungi Super Admin.',
                        ]);
                    }

                    $this->tenantManager->setTenant($tenant);
                }
            }
        }

        return $next($request);
    }
}
