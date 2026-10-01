<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiGatewayAndWhatsAppQrTest extends TestCase
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

    public function test_can_configure_midtrans_xendit_and_tripay_gateways(): void
    {
        // 1. Configure Midtrans
        $response = $this->actingAs($this->user)->post(route('tenant.payment-methods.update-gateway'), [
            'provider' => 'MIDTRANS',
            'server_key' => 'SB-Mid-server-TESTKEY123',
            'client_key' => 'SB-Mid-client-TESTKEY123',
            'environment' => 'sandbox',
            'is_active' => '1',
        ]);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('payment_methods', [
            'tenant_id' => $this->tenant->id,
            'provider' => 'MIDTRANS',
            'type' => 'GATEWAY',
            'is_active' => 1,
        ]);

        // 2. Configure Xendit
        $response = $this->actingAs($this->user)->post(route('tenant.payment-methods.update-gateway'), [
            'provider' => 'XENDIT',
            'secret_key' => 'xnd_development_TESTSECRET123',
            'webhook_token' => 'xendit_wh_token_abc',
            'environment' => 'sandbox',
            'is_active' => '1',
        ]);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('payment_methods', [
            'tenant_id' => $this->tenant->id,
            'provider' => 'XENDIT',
            'type' => 'GATEWAY',
            'is_active' => 1,
        ]);

        // 3. Configure Tripay
        $response = $this->actingAs($this->user)->post(route('tenant.payment-methods.update-gateway'), [
            'provider' => 'TRIPAY',
            'merchant_code' => 'T12345',
            'api_key' => 'DEV-TRIPAY-KEY',
            'private_key' => 'TRIPAY-PRIVATE-KEY',
            'environment' => 'sandbox',
            'is_active' => '1',
        ]);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('payment_methods', [
            'tenant_id' => $this->tenant->id,
            'provider' => 'TRIPAY',
            'type' => 'GATEWAY',
            'is_active' => 1,
        ]);
    }

    public function test_whatsapp_qr_connect_flow(): void
    {
        // 1. Generate QR code
        $genResponse = $this->actingAs($this->user)->postJson(route('tenant.notifications.qr.generate'));
        $genResponse->assertOk();
        $genResponse->assertJson(['success' => true, 'status' => 'WAITING_SCAN']);

        // 2. Simulate Pair
        $pairResponse = $this->actingAs($this->user)->getJson(route('tenant.notifications.qr.status', ['simulate_pair' => 1]));
        $pairResponse->assertOk();
        $pairResponse->assertJson(['status' => 'CONNECTED']);

        // 3. Disconnect QR
        $discResponse = $this->actingAs($this->user)->postJson(route('tenant.notifications.qr.disconnect'));
        $discResponse->assertOk();
        $discResponse->assertJson(['success' => true]);

        // Verify status is DISCONNECTED
        $statusResponse = $this->actingAs($this->user)->getJson(route('tenant.notifications.qr.status'));
        $statusResponse->assertJson(['status' => 'DISCONNECTED']);
    }
}
