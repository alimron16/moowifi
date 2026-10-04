<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and apply production-grade security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Prevent MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 2. Prevent Clickjacking (allow same origin for modals/iframes if needed)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. XSS Filter protection for legacy browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 4. Referrer Policy: hide sensitive URLs across origins
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Permissions Policy (Disables microphone, camera, geolocation if not needed)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(self)');

        // 6. Cross-Origin policies
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        return $response;
    }
}
