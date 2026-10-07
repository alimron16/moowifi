<?php

namespace Tests\Feature;

use App\Models\SaasPlan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TenantSubscriptionUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected Tenant $tenant;
    protected User $user;
    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::first();
        $this->user = User::where('tenant_id', $this->tenant->id)->first();
        $this->superAdmin = User::where('role', 'SUPER_ADMIN')->first();
    }

    public function test_tenant_can_view_subscription_plans_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('tenant.subscription.index'));
        $response->assertStatus(200);
        $response->assertSee('Langganan & Upgrade Paket MooWiFi', false);
        $response->assertSee('Standar');
    }

    public function test_tenant_creates_order_and_submits_payment_proof_then_admin_approves(): void
    {
        Storage::fake('public');

        $planEnterprise = SaasPlan::where('code', 'ENTERPRISE')->first();

        // 1. Tenant memilih paket Enterprise di modal -> diarahkan ke halaman pembayaran
        $response = $this->actingAs($this->user)->post(route('tenant.subscription.upgrade'), [
            'saas_plan_id' => $planEnterprise->id,
            'billing_cycle' => 'monthly',
        ]);

        $order = Subscription::where('tenant_id', $this->tenant->id)->where('status', 'PENDING')->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('tenant.subscription.payment', $order->id));

        // 2. Tenant membuka halaman instruksi pembayaran
        $paymentPage = $this->actingAs($this->user)->get(route('tenant.subscription.payment', $order->id));
        $paymentPage->assertStatus(200);
        $paymentPage->assertSee('Pembayaran Tagihan Langganan Paket');
        $paymentPage->assertSee('Rekening Resmi Pembayaran Platform');

        // 3. Tenant mengunggah bukti transfer
        $proofFile = UploadedFile::fake()->image('bukti_transfer.jpg');
        $confirmResponse = $this->actingAs($this->user)->post(route('tenant.subscription.confirm-payment', $order->id), [
            'payment_method' => 'BCA - 1234567890',
            'proof' => $proofFile,
        ]);

        $confirmResponse->assertSessionHas('success');
        $this->assertEquals('WAITING_VERIFICATION', $order->fresh()->status);

        // 4. Super Admin menyetujui pesanan langganan
        $approveResponse = $this->actingAs($this->superAdmin)->post(route('super-admin.subscriptions.approve', $order->id));
        $approveResponse->assertSessionHas('success');

        $this->assertEquals('ACTIVE', $order->fresh()->status);
        $this->assertEquals(2000, $this->tenant->fresh()->getMaxCustomers());
        $this->assertEquals(5, $this->tenant->fresh()->getMaxRouters());
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

    public function test_tenant_can_pay_subscription_online_via_duitku_and_webhook_activates_it(): void
    {
        \App\Models\PlatformSetting::set('platform_gateways', [
            'DUITKU' => [
                'merchant_code' => 'D12345',
                'api_key' => 'secret12345',
                'environment' => 'sandbox',
                'is_active' => true,
            ],
        ]);

        $planEnterprise = SaasPlan::where('code', 'ENTERPRISE')->first();

        // 1. Order enterprise
        $this->actingAs($this->user)->post(route('tenant.subscription.upgrade'), [
            'saas_plan_id' => $planEnterprise->id,
            'billing_cycle' => 'monthly',
        ]);

        $order = Subscription::where('saas_plan_id', $planEnterprise->id)->first();
        $this->assertNotNull($order);

        // 2. Akses halaman pembayaran -> melihat opsi Duitku
        $paymentPage = $this->actingAs($this->user)->get(route('tenant.subscription.payment', $order->id));
        $paymentPage->assertSee('Bayar Otomatis');

        // 3. Simulasi webhook Duitku sukses
        $merchantCode = 'D12345';
        $amount = (string)(int)round($order->amount);
        $merchantOrderId = $order->order_number;
        $apiKey = 'secret12345';
        $signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        $webhookResponse = $this->postJson('/api/webhooks/duitku', [
            'merchantCode' => $merchantCode,
            'amount' => $amount,
            'merchantOrderId' => $merchantOrderId,
            'signature' => $signature,
            'resultCode' => '00',
        ]);

        $webhookResponse->assertStatus(200);
        $this->assertEquals('ACTIVE', $order->fresh()->status);
        $this->assertEquals('DUITKU', $order->fresh()->payment_method);
        $this->assertEquals(2000, $this->tenant->fresh()->getMaxCustomers());
    }
}
