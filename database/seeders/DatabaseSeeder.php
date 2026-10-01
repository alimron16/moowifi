<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Router;
use App\Models\SaasPlan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin User
        User::create([
            'tenant_id' => null,
            'name' => 'Super Admin SaaS',
            'email' => 'admin@mwifi.id',
            'phone' => '081111111111',
            'password' => Hash::make('password'),
            'role' => 'SUPER_ADMIN',
            'status' => 'ACTIVE',
        ]);

        // 2. SaaS Plans
        $planStarter = SaasPlan::create([
            'name' => 'Starter 100 User',
            'code' => 'STARTER',
            'price' => 100000,
            'max_customers' => 100,
            'max_routers' => 1,
            'is_active' => true,
        ]);

        $planPro = SaasPlan::create([
            'name' => 'Pro 500 User',
            'code' => 'PRO',
            'price' => 250000,
            'max_customers' => 500,
            'max_routers' => 3,
            'is_active' => true,
        ]);

        $planEnterprise = SaasPlan::create([
            'name' => 'Enterprise Unlimited',
            'code' => 'ENTERPRISE',
            'price' => 500000,
            'max_customers' => 0,
            'max_routers' => 10,
            'is_active' => true,
        ]);

        // 3. Demo Tenant: BudiNet
        $tenant = Tenant::create([
            'code' => 'BDN',
            'name' => 'BudiNet Nusantara',
            'slug' => 'budinet-nusantara',
            'email' => 'kontak@budinet.id',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Cikarang, Jawa Barat',
            'status' => 'ACTIVE',
            'trial_ends_at' => now()->addDays(30),
            'settings' => [
                'whatsapp' => [
                    'provider' => 'FONNTE',
                    'token' => 'sample-fonnte-token',
                ],
            ],
        ]);

        // Subscription for Tenant
        Subscription::create([
            'tenant_id' => $tenant->id,
            'saas_plan_id' => $planPro->id,
            'status' => 'ACTIVE',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Tenant Owner User
        User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@budinet.id',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'OWNER',
            'status' => 'ACTIVE',
        ]);

        // Tenant Staff
        User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Agus Teknisi',
            'email' => 'agus@budinet.id',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'TECHNICIAN',
            'status' => 'ACTIVE',
        ]);

        // 4. Demo Packages
        $pkg10m = Package::create([
            'tenant_id' => $tenant->id,
            'name' => '10 Mbps Hemat',
            'price' => 100000,
            'download_speed' => '10M',
            'upload_speed' => '5M',
            'mikrotik_profile' => 'profile_10m',
            'description' => 'Cocok untuk browsing dan streaming ringan.',
            'status' => 'ACTIVE',
        ]);

        $pkg20m = Package::create([
            'tenant_id' => $tenant->id,
            'name' => '20 Mbps Cepat',
            'price' => 150000,
            'download_speed' => '20M',
            'upload_speed' => '10M',
            'mikrotik_profile' => 'profile_20m',
            'description' => 'Cocok untuk keluarga dan streaming HD.',
            'status' => 'ACTIVE',
        ]);

        $pkg50m = Package::create([
            'tenant_id' => $tenant->id,
            'name' => '50 Mbps Gaming',
            'price' => 250000,
            'download_speed' => '50M',
            'upload_speed' => '25M',
            'mikrotik_profile' => 'profile_50m',
            'description' => 'Kecepatan maksimal untuk game online dan kantor.',
            'status' => 'ACTIVE',
        ]);

        // 5. Demo Router
        $router = Router::create([
            'tenant_id' => $tenant->id,
            'name' => 'Router Core OLT BudiNet',
            'connection_type' => 'VPN_TUNNEL',
            'tunnel_ip' => '10.99.1.20',
            'port' => 8728,
            'username' => 'mwifi_api',
            'password' => 'secret123',
            'vpn_user' => 'vpn_bdn_core',
            'vpn_password' => 'vpnpass123',
            'status' => 'ONLINE',
            'last_seen_at' => now(),
        ]);

        // 6. Payment Methods
        PaymentMethod::create([
            'tenant_id' => $tenant->id,
            'type' => 'MANUAL',
            'provider' => 'BCA',
            'name' => 'Bank Central Asia (BCA)',
            'account_number' => '1234567890',
            'account_name' => 'Budi Santoso',
            'instructions' => 'Transfer tepat sesuai nominal tagihan dan simpan bukti struk transfer.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        PaymentMethod::create([
            'tenant_id' => $tenant->id,
            'type' => 'MANUAL',
            'provider' => 'DANA',
            'name' => 'DANA / QRIS',
            'account_number' => '081234567890',
            'account_name' => 'BudiNet Official',
            'instructions' => 'Kirim ke nomor DANA atau scan QRIS.',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        PaymentMethod::create([
            'tenant_id' => $tenant->id,
            'type' => 'GATEWAY',
            'provider' => 'DUITKU',
            'name' => 'Duitku Payment Gateway',
            'encrypted_credentials' => json_encode([
                'merchant_code' => 'D12345_DEMO',
                'api_key' => 'sample_api_key_duitku',
                'environment' => 'sandbox',
            ]),
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 7. Customers
        $cust1 = Customer::create([
            'tenant_id' => $tenant->id,
            'package_id' => $pkg20m->id,
            'router_id' => $router->id,
            'customer_code' => 'CUST-0001',
            'name' => 'Andi Wijaya',
            'phone' => '081298765432',
            'email' => 'andi@gmail.com',
            'address' => 'Blok B No. 12, RT 02 / RW 05',
            'connection_type' => 'PPPOE',
            'mikrotik_username' => 'andi_bdn',
            'encrypted_mikrotik_password' => 'secret123',
            'billing_type' => 'PREPAID',
            'billing_day' => 1,
            'due_day' => 10,
            'grace_period_days' => 3,
            'status' => 'ACTIVE',
            'auto_cut_enabled' => true,
            'installation_date' => now()->subMonths(3)->toDateString(),
        ]);

        $cust2 = Customer::create([
            'tenant_id' => $tenant->id,
            'package_id' => $pkg10m->id,
            'router_id' => $router->id,
            'customer_code' => 'CUST-0002',
            'name' => 'Siti Rahma',
            'phone' => '081345678901',
            'email' => 'siti@gmail.com',
            'address' => 'Blok C No. 04, RT 03 / RW 05',
            'connection_type' => 'PPPOE',
            'mikrotik_username' => 'siti_bdn',
            'encrypted_mikrotik_password' => 'secret123',
            'billing_type' => 'PREPAID',
            'billing_day' => 1,
            'due_day' => 10,
            'grace_period_days' => 3,
            'status' => 'UNPAID',
            'auto_cut_enabled' => true,
            'installation_date' => now()->subMonths(2)->toDateString(),
        ]);

        $cust3 = Customer::create([
            'tenant_id' => $tenant->id,
            'package_id' => $pkg10m->id,
            'router_id' => $router->id,
            'customer_code' => 'CUST-0003',
            'name' => 'Eko Prasetyo',
            'phone' => '081567890123',
            'email' => 'eko@gmail.com',
            'address' => 'Blok A No. 19, RT 01 / RW 05',
            'connection_type' => 'PPPOE',
            'mikrotik_username' => 'eko_bdn',
            'encrypted_mikrotik_password' => 'secret123',
            'billing_type' => 'PREPAID',
            'billing_day' => 1,
            'due_day' => 10,
            'grace_period_days' => 3,
            'status' => 'ISOLATED',
            'auto_cut_enabled' => true,
            'installation_date' => now()->subMonths(5)->toDateString(),
        ]);

        // 8. Invoices & Payments
        // Invoice 1: Andi (LUNAS)
        $inv1 = Invoice::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $cust1->id,
            'invoice_number' => 'INV/202610/BDN/0001',
            'payment_token' => (string) Str::uuid(),
            'issue_date' => now()->startOfMonth()->toDateString(),
            'due_date' => now()->startOfMonth()->addDays(9)->toDateString(),
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'subtotal' => $pkg20m->price,
            'total_amount' => $pkg20m->price,
            'paid_amount' => $pkg20m->price,
            'status' => 'PAID',
            'paid_at' => now()->subDays(2),
        ]);
        InvoiceItem::create([
            'invoice_id' => $inv1->id,
            'description' => 'Paket Internet ' . $pkg20m->name,
            'quantity' => 1,
            'unit_price' => $pkg20m->price,
            'total_price' => $pkg20m->price,
        ]);
        Payment::create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $inv1->id,
            'customer_id' => $cust1->id,
            'payment_code' => 'PAY-202610-0001',
            'amount' => $pkg20m->price,
            'status' => 'SUCCESS',
            'paid_at' => now()->subDays(2),
        ]);

        // Invoice 2: Siti (UNPAID)
        $inv2 = Invoice::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $cust2->id,
            'invoice_number' => 'INV/202610/BDN/0002',
            'payment_token' => (string) Str::uuid(),
            'issue_date' => now()->startOfMonth()->toDateString(),
            'due_date' => now()->startOfMonth()->addDays(9)->toDateString(),
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'subtotal' => $pkg10m->price,
            'total_amount' => $pkg10m->price,
            'paid_amount' => 0,
            'status' => 'UNPAID',
        ]);
        InvoiceItem::create([
            'invoice_id' => $inv2->id,
            'description' => 'Paket Internet ' . $pkg10m->name,
            'quantity' => 1,
            'unit_price' => $pkg10m->price,
            'total_price' => $pkg10m->price,
        ]);

        // Invoice 3: Eko (OVERDUE - ISOLATED)
        $inv3 = Invoice::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $cust3->id,
            'invoice_number' => 'INV/202610/BDN/0003',
            'payment_token' => (string) Str::uuid(),
            'issue_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'due_date' => now()->subMonth()->startOfMonth()->addDays(9)->toDateString(),
            'period_start' => now()->subMonth()->startOfMonth()->toDateString(),
            'period_end' => now()->subMonth()->endOfMonth()->toDateString(),
            'subtotal' => $pkg10m->price,
            'total_amount' => $pkg10m->price,
            'paid_amount' => 0,
            'status' => 'OVERDUE',
        ]);
        InvoiceItem::create([
            'invoice_id' => $inv3->id,
            'description' => 'Paket Internet ' . $pkg10m->name,
            'quantity' => 1,
            'unit_price' => $pkg10m->price,
            'total_price' => $pkg10m->price,
        ]);
    }
}
