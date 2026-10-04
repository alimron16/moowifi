<?php

use App\Models\SaasPlan;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $tiers = [
            [
                'code' => 'FREE',
                'name' => 'Gratis',
                'price' => 0,
                'description' => 'Cocok untuk pemula atau rintisan RT/RW Net skala kecil.',
                'max_customers' => 20,
                'max_routers' => 1,
                'features' => [
                    '20 Pelanggan Aktif',
                    '1 Akses MikroTik',
                    'Manajemen Tugas (terbatas)',
                    'WhatsApp Gateway (Wiku / QR Device)',
                    'Gratis Auto-VPN Tunnel',
                    'Server RADIUS Ready',
                    'Billing & Isolir Otomatis',
                ],
                'is_active' => true,
            ],
            [
                'code' => 'STANDAR',
                'name' => 'Standar',
                'price' => 59000,
                'description' => 'Cocok untuk pemula atau RT/RW Net skala kecil.',
                'max_customers' => 150,
                'max_routers' => 1,
                'features' => [
                    '150 Pelanggan Aktif',
                    '1 Akses MikroTik',
                    'Manajemen Tugas (Full Akses)',
                    '1 WhatsApp Gateway',
                    'Gratis Auto-VPN Tunnel',
                    'Server RADIUS Ready',
                    'Payment Gateway & Transfer Manual',
                    'Auto-Cut & Auto-Restore MikroTik',
                ],
                'is_active' => true,
            ],
            [
                'code' => 'PRO',
                'name' => 'Pro',
                'price' => 119000,
                'description' => 'Solusi lengkap & performa terbaik untuk ISP Profesional & RT/RW Net berkembang.',
                'max_customers' => 450,
                'max_routers' => 2,
                'features' => [
                    '450 Pelanggan Aktif',
                    '2 Akses MikroTik',
                    'Manajemen Tugas (Full Akses)',
                    '1 WhatsApp Gateway',
                    'Gratis Auto-VPN Tunnel',
                    'Server RADIUS Ready',
                    'Multi Payment Gateway (Duitku, Midtrans, Xendit, Tripay)',
                    'Auto-Cut & Auto-Restore Realtime',
                ],
                'is_active' => true,
            ],
            [
                'code' => 'BISNIS',
                'name' => 'Bisnis',
                'price' => 179000,
                'description' => 'Cocok untuk pemula atau skala berkembang antar-desa.',
                'max_customers' => 750,
                'max_routers' => 3,
                'features' => [
                    '750 Pelanggan Aktif',
                    '3 Akses MikroTik',
                    'Manajemen Tugas (Full Akses)',
                    '1 WhatsApp Gateway',
                    'Gratis Auto-VPN Tunnel',
                    'Server RADIUS Ready',
                    'Otomasi Billing & Rekonsiliasi Finansial',
                    'Monitoring Router & Auto-Health Check',
                ],
                'is_active' => true,
            ],
            [
                'code' => 'ENTERPRISE',
                'name' => 'Enterprise',
                'price' => 299000,
                'description' => 'Cocok untuk pemula atau skala berkembang multi-wilayah.',
                'max_customers' => 2000,
                'max_routers' => 5,
                'features' => [
                    '2.000 Pelanggan Aktif',
                    '5 Akses MikroTik',
                    'Manajemen Tugas (Full Akses)',
                    '2 WhatsApp Gateway',
                    'Gratis Auto-VPN Tunnel',
                    'Server RADIUS Ready',
                    'Prioritas Support Teknis 24/7',
                    'Custom Domain & Rekonsiliasi Multi-Gateway',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            SaasPlan::updateOrCreate(
                ['code' => $tier['code']],
                $tier
            );
        }

        // If STARTER was present before, deactivate or alias it
        SaasPlan::where('code', 'STARTER')->update(['is_active' => false]);
    }

    public function down(): void
    {
        // Revert not required as this is an additive data alignment
    }
};
