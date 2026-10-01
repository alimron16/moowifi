<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantRole
{
    /**
     * Handle an incoming request and verify tenant module permission
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->canAccessModule($module)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Akses ditolak: Peran akun Anda (' . $user->role . ') tidak memiliki izin untuk modul ini.',
                ], 403);
            }

            return redirect()->route('tenant.dashboard')->with('error', 'Akses ditolak: Akun Anda dengan peran ' . $user->role . ' tidak memiliki izin untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}
