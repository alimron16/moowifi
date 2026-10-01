<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;

class CompleteSystemCoverageTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected bool $seed = true;

    public function test_super_admin_panel_endpoints()
    {
        $admin = User::where('role', 'SUPER_ADMIN')->first();
        $this->assertNotNull($admin);

        $routes = [
            '/super-admin/dashboard',
            '/super-admin/tenants',
            '/super-admin/plans',
            '/super-admin/subscriptions',
            '/super-admin/payments',
            '/super-admin/monitoring',
            '/super-admin/payment-providers',
            '/super-admin/whatsapp',
            '/super-admin/email',
            '/super-admin/logs',
            '/super-admin/announcements',
            '/super-admin/support',
            '/super-admin/settings',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_tenant_admin_panel_endpoints()
    {
        $owner = User::where('email', 'budi@budinet.id')->first();
        $this->assertNotNull($owner);

        $routes = [
            '/dashboard',
            '/customers',
            '/customers/create',
            '/invoices',
            '/invoices/create',
            '/billing/payments',
            '/billing/manual-payments',
            '/packages',
            '/routers',
            '/routers/profiles',
            '/routers/online-users',
            '/routers/logs',
            '/payments/methods',
            '/payments/gateway',
            '/payments/transactions',
            '/notifications',
            '/notifications/logs',
            '/reports',
            '/settings',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($owner)->get($route);
            $response->assertStatus(200);
        }
    }
}
