<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantBillingTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_cleanly(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('MooWiFi');
        $response->assertSee('Masuk ke Dasbor');
    }

    public function test_tenant_owner_can_login_and_view_dashboard(): void
    {
        $user = User::where('email', 'budi@budinet.id')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('BudiNet Nusantara');
        $response->assertSee('Pelanggan Aktif');
        $response->assertSee('Router MikroTik');
    }

    public function test_super_admin_can_access_super_admin_dashboard(): void
    {
        $admin = User::where('role', 'SUPER_ADMIN')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/super-admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('SUPER ADMIN');
        $response->assertSee('Kelola Tenant RT/RW Net');
    }

    public function test_public_payment_page_accessible_without_login(): void
    {
        $invoice = Invoice::withoutGlobalScopes()->first();
        $this->assertNotNull($invoice);

        $response = $this->get('/pay/' . $invoice->payment_token);
        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
        $response->assertSee($invoice->customer->name);
    }
}
