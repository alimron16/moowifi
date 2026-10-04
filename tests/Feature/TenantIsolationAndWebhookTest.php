<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TenantIsolationAndWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_tenant_b_cannot_see_or_modify_tenant_a_customer(): void
    {
        // Create Tenant B
        $tenantB = Tenant::create([
            'code' => 'ANDI',
            'name' => 'AndiNet Digital',
            'slug' => 'andinet-digital',
            'status' => 'ACTIVE',
        ]);

        $userB = User::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Andi Pratama',
            'email' => 'andi@andinet.id',
            'phone' => '089999999999',
            'password' => Hash::make('password'),
            'role' => 'OWNER',
            'status' => 'ACTIVE',
            'email_verified_at' => now(),
        ]);

        // Customer from Tenant A (BudiNet)
        $customerA = Customer::where('customer_code', 'CUST-0001')->first();
        $this->assertNotNull($customerA);

        // When Tenant B accesses Tenant A's customer edit page, it must return 404
        $response = $this->actingAs($userB)->get("/customers/{$customerA->id}/edit");
        $response->assertStatus(404);

        // When Tenant B visits customer index, Customer A should not be visible
        $responseIndex = $this->actingAs($userB)->get('/customers');
        $responseIndex->assertStatus(200);
        $responseIndex->assertDontSee($customerA->name);
    }

    public function test_duitku_webhook_successfully_marks_invoice_paid(): void
    {
        $invoice = Invoice::withoutGlobalScopes()->where('status', 'UNPAID')->first();
        $this->assertNotNull($invoice);

        $paymentMethod = PaymentMethod::withoutGlobalScopes()
            ->where('tenant_id', $invoice->tenant_id)
            ->where('provider', 'DUITKU')
            ->first();

        $merchantCode = 'D12345_DEMO';
        $apiKey = 'sample_api_key_duitku';
        $amount = (int) round($invoice->total_amount);
        $merchantOrderId = $invoice->invoice_number;

        // MD5(merchantCode + amount + merchantOrderId + apiKey)
        $signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        $payload = [
            'merchantCode' => $merchantCode,
            'amount' => $amount,
            'merchantOrderId' => $merchantOrderId,
            'signature' => $signature,
            'resultCode' => '00',
            'reference' => 'REF-DUITKU-123456',
            'paymentCode' => 'VA_BCA',
        ];

        $response = $this->postJson('/api/webhooks/duitku', $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => '00']);

        // Assert invoice is now PAID
        $this->assertEquals('PAID', $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);

        // Assert customer is now ACTIVE
        $this->assertEquals('ACTIVE', $invoice->customer->fresh()->status);
    }
}
