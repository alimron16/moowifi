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

    public function test_midtrans_webhook_successfully_marks_invoice_paid(): void
    {
        $serverKey = 'SB-Mid-server-TESTKEY999';
        PaymentMethod::updateOrCreate(
            ['tenant_id' => $this->tenant->id, 'provider' => 'MIDTRANS'],
            [
                'type' => 'GATEWAY',
                'name' => 'Midtrans Gateway',
                'credentials' => ['server_key' => $serverKey, 'environment' => 'sandbox'],
                'is_active' => true,
            ]
        );

        $invoice = Invoice::withoutGlobalScopes()->where('tenant_id', $this->tenant->id)->where('status', 'UNPAID')->first();
        $this->assertNotNull($invoice);

        $orderId = $invoice->invoice_number;
        $statusCode = '200';
        $grossAmount = number_format($invoice->total_amount, 2, '.', '');
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'transaction_id' => 'TRX-MID-12345',
            'payment_type' => 'qris',
        ];

        $response = $this->postJson('/api/webhooks/midtrans', $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'OK']);

        $this->assertEquals('PAID', $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);
        $this->assertEquals('ACTIVE', $invoice->customer->fresh()->status);
    }

    public function test_xendit_webhook_successfully_marks_invoice_paid(): void
    {
        $webhookToken = 'xendit_test_token_secret_123';
        PaymentMethod::updateOrCreate(
            ['tenant_id' => $this->tenant->id, 'provider' => 'XENDIT'],
            [
                'type' => 'GATEWAY',
                'name' => 'Xendit Gateway',
                'credentials' => [
                    'secret_key' => 'xnd_development_test',
                    'webhook_token' => $webhookToken,
                    'environment' => 'sandbox',
                ],
                'is_active' => true,
            ]
        );

        $invoice = Invoice::withoutGlobalScopes()->where('tenant_id', $this->tenant->id)->where('status', 'UNPAID')->first();
        $this->assertNotNull($invoice);

        $payload = [
            'id' => 'xendit_invoice_ref_123',
            'external_id' => $invoice->invoice_number,
            'status' => 'PAID',
            'amount' => (int) round($invoice->total_amount),
            'paid_amount' => (int) round($invoice->total_amount),
            'payment_method' => 'BANK_TRANSFER',
        ];

        $response = $this->postJson('/api/webhooks/xendit', $payload, [
            'x-callback-token' => $webhookToken,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);

        $this->assertEquals('PAID', $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);
        $this->assertEquals('ACTIVE', $invoice->customer->fresh()->status);
    }

    public function test_tripay_webhook_successfully_marks_invoice_paid(): void
    {
        $privateKey = 'tripay_private_secret_key_123';
        PaymentMethod::updateOrCreate(
            ['tenant_id' => $this->tenant->id, 'provider' => 'TRIPAY'],
            [
                'type' => 'GATEWAY',
                'name' => 'Tripay Gateway',
                'credentials' => [
                    'merchant_code' => 'T12345',
                    'api_key' => 'DEV-KEY',
                    'private_key' => $privateKey,
                    'environment' => 'sandbox',
                ],
                'is_active' => true,
            ]
        );

        $invoice = Invoice::withoutGlobalScopes()->where('tenant_id', $this->tenant->id)->where('status', 'UNPAID')->first();
        $this->assertNotNull($invoice);

        $payload = [
            'reference' => 'DEV-T12345678',
            'merchant_ref' => $invoice->invoice_number,
            'payment_method' => 'QRIS2',
            'status' => 'PAID',
            'total_amount' => (int) round($invoice->total_amount),
        ];

        $rawJson = json_encode($payload);
        $signature = hash_hmac('sha256', $rawJson, $privateKey);

        $response = $this->call(
            'POST',
            '/api/webhooks/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Callback-Signature' => $signature,
            ],
            $rawJson
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals('PAID', $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);
        $this->assertEquals('ACTIVE', $invoice->customer->fresh()->status);
    }

    public function test_can_upload_qris_manual_payment_method_and_display_on_public_payment_page(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $file = \Illuminate\Http\UploadedFile::fake()->image('my-qris-barcode.png', 400, 400);

        $response = $this->actingAs($this->user)->post(route('tenant.payment-methods.store-manual'), [
            'provider' => 'QRIS',
            'name' => 'QRIS All Payment BudiNet',
            'account_number' => 'NMID1234567890',
            'account_name' => 'BudiNet Store',
            'instructions' => 'Scan QRIS lalu kirim bukti transfer.',
            'qr_code_image' => $file,
        ]);

        $response->assertSessionHasNoErrors();

        $method = PaymentMethod::where('tenant_id', $this->tenant->id)
            ->where('provider', 'QRIS')
            ->first();

        $this->assertNotNull($method);
        $this->assertNotNull($method->qr_code_image);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($method->qr_code_image);

        // Verify public invoice payment page renders QRIS image
        $invoice = Invoice::withoutGlobalScopes()->where('tenant_id', $this->tenant->id)->first();
        $this->assertNotNull($invoice);

        $payPageResponse = $this->get(route('payment.show', $invoice->payment_token));
        $payPageResponse->assertOk();
        $payPageResponse->assertSee('QRIS Pembayaran');
        $payPageResponse->assertSee($method->name);
        $payPageResponse->assertSee(asset('storage/' . $method->qr_code_image));
        $payPageResponse->assertSee('Simpan Gambar QRIS');
    }

    public function test_platform_subscription_supports_all_gateways_webhook(): void
    {
        $subscription = \App\Models\Subscription::create([
            'tenant_id' => $this->tenant->id,
            'saas_plan_id' => \App\Models\SaasPlan::first()->id,
            'order_number' => 'ORD-SUB-' . date('Ym') . '-9999',
            'amount' => 149000,
            'billing_cycle' => 'monthly',
            'status' => 'PENDING',
        ]);

        // Configure Midtrans Platform
        \App\Models\PlatformSetting::set('platform_gateways', [
            'MIDTRANS' => [
                'server_key' => 'SB-Mid-server-PLATFORM-SECRET',
                'client_key' => 'SB-Mid-client-PLATFORM',
                'environment' => 'sandbox',
                'is_active' => true,
            ],
        ]);

        $serverKey = 'SB-Mid-server-PLATFORM-SECRET';
        $orderId = $subscription->order_number;
        $statusCode = '200';
        $grossAmount = '149000.00';
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'transaction_id' => 'TRX-SUB-MID-9999',
            'payment_type' => 'qris',
        ];

        $response = $this->postJson('/api/webhooks/midtrans', $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'OK']);

        $this->assertEquals('ACTIVE', $subscription->fresh()->status);
        $this->assertEquals('ACTIVE', $this->tenant->fresh()->status);
    }

    public function test_manual_payment_unique_3_digit_code_for_subscription_and_invoice(): void
    {
        // 1. Subscription unique code
        $subscription = \App\Models\Subscription::create([
            'tenant_id' => $this->tenant->id,
            'saas_plan_id' => \App\Models\SaasPlan::first()->id,
            'order_number' => 'ORD-SUB-' . date('Ym') . '-1234',
            'amount' => 149000,
            'billing_cycle' => 'monthly',
            'status' => 'PENDING',
        ]);

        $this->assertGreaterThanOrEqual(100, $subscription->unique_code);
        $this->assertLessThanOrEqual(999, $subscription->unique_code);
        $this->assertEquals($subscription->amount + $subscription->unique_code, $subscription->manual_amount);

        // Check subscription payment page renders unique code
        $subPageResponse = $this->actingAs($this->user)->get(route('tenant.subscription.payment', $subscription->id));
        $subPageResponse->assertOk();
        $subPageResponse->assertSee('Total Nominal Transfer Manual (Termasuk 3 Digit Kode Unik)');
        $subPageResponse->assertSee(number_format($subscription->manual_amount, 0, ',', '.'));

        // 2. Customer invoice unique code
        $invoice = Invoice::withoutGlobalScopes()->where('tenant_id', $this->tenant->id)->first();
        $this->assertNotNull($invoice);
        $this->assertGreaterThanOrEqual(100, $invoice->unique_code);
        $this->assertLessThanOrEqual(999, $invoice->unique_code);
        $this->assertEquals($invoice->total_amount + $invoice->unique_code, $invoice->manual_amount);

        // Check public payment page renders unique code
        $payPageResponse = $this->get(route('payment.show', $invoice->payment_token));
        $payPageResponse->assertOk();
        $payPageResponse->assertSee('Total Nominal Transfer Manual (Termasuk 3 Digit Kode Unik)');
        $payPageResponse->assertSee(number_format($invoice->manual_amount, 0, ',', '.'));
    }
}
