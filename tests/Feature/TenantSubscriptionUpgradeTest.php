<?php

namespace Tests\Feature;

use App\Models\SaasPlan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSubscriptionUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected Tenant $tenant;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::first();
        $this->user = User::where('tenant_id', $this->tenant->id)->first();
    }

    public function test_tenant_can_view_subscription_plans_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('tenant.subscription.index'));
        $response->assertStatus(200);
        $response->assertSee('Langganan & Upgrade Paket MooWiFi', false);
        $response->assertSee('Standar');
    }

    public function test_tenant_can_upgrade_plan_successfully(): void
    {
        $planPro = SaasPlan::where('code', 'PRO')->first();

        // Lakukan upgrade ke Pro
        $response = $this->actingAs($this->user)->post(route('tenant.subscription.upgrade'), [
            'saas_plan_id' => $planPro->id,
            'billing_cycle' => 'monthly',
        ]);

        $response->assertRedirect(route('tenant.dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals(450, $this->tenant->fresh()->getMaxCustomers());
        $this->assertEquals(2, $this->tenant->fresh()->getMaxRouters());
    }

    public function test_tenant_cannot_downgrade_plan_self_service(): void
    {
        $planStandar = SaasPlan::where('code', 'STANDAR')->first();
        $planPro = SaasPlan::where('code', 'PRO')->first();

        // Jadikan subscription awal PRO
        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'saas_plan_id' => $planPro->id,
            'status' => 'ACTIVE',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Coba downgrade ke Standar (Ditolak)
        $response = $this->actingAs($this->user)->post(route('tenant.subscription.upgrade'), [
            'saas_plan_id' => $planStandar->id,
            'billing_cycle' => 'monthly',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(450, $this->tenant->fresh()->getMaxCustomers());
    }
}
