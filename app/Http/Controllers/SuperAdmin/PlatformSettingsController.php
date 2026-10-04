<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PlatformSetting;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformSettingsController extends Controller
{
    public function settings(Request $request)
    {
        $settings = PlatformSetting::get('platform_profile', [
            'app_name' => 'MooWiFi - Otomatisasi Jaringan, Maksimalkan Cuan.',
            'primary_domain' => 'app.domain.com',
            'default_trial_days' => 14,
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'contact_email' => 'support@moowifi.id',
            'contact_phone' => '081122334455',
        ]);

        return view('super-admin.settings.platform', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:150'],
            'primary_domain' => ['required', 'string', 'max:100'],
            'default_trial_days' => ['required', 'integer', 'min:1', 'max:90'],
            'contact_email' => ['nullable', 'email', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'timezone' => ['required', 'string'],
        ]);

        $validated['currency'] = 'IDR';

        PlatformSetting::set('platform_profile', $validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'PLATFORM_SETTINGS_UPDATED',
            'description' => 'Super Admin memperbarui preferensi umum platform.',
        ]);

        return back()->with('success', 'Pengaturan umum platform berhasil disimpan.');
    }

    public function paymentProviders()
    {
        $gateways = PlatformSetting::get('platform_gateways', [
            'DUITKU' => [
                'merchant_code' => '',
                'api_key' => '',
                'environment' => 'sandbox',
                'is_active' => false,
            ],
            'MIDTRANS' => [
                'server_key' => '',
                'client_key' => '',
                'environment' => 'sandbox',
                'is_active' => false,
            ],
            'XENDIT' => [
                'secret_key' => '',
                'webhook_token' => '',
                'environment' => 'sandbox',
                'is_active' => false,
            ],
            'TRIPAY' => [
                'merchant_code' => '',
                'api_key' => '',
                'private_key' => '',
                'environment' => 'sandbox',
                'is_active' => false,
            ],
        ]);

        $manualBanks = PlatformSetting::get('platform_manual_banks', [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT MooWiFi Digital Indonesia',
                'instructions' => 'Transfer nominal tepat sesuai invoice langganan.',
                'is_active' => true,
            ],
        ]);

        return view('super-admin.settings.payment-providers', compact('gateways', 'manualBanks'));
    }

    public function updatePaymentProviders(Request $request)
    {
        $type = $request->input('update_type', 'gateway');

        if ($type === 'manual_bank') {
            $banks = [];
            $bankNames = $request->input('bank_names', []);
            $accNumbers = $request->input('account_numbers', []);
            $accNames = $request->input('account_names', []);
            $instructions = $request->input('instructions', []);

            foreach ($bankNames as $index => $name) {
                if (!empty($name) && !empty($accNumbers[$index])) {
                    $banks[] = [
                        'bank_name' => $name,
                        'account_number' => $accNumbers[$index],
                        'account_name' => $accNames[$index] ?? '',
                        'instructions' => $instructions[$index] ?? '',
                        'is_active' => true,
                    ];
                }
            }

            PlatformSetting::set('platform_manual_banks', $banks);

            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'PLATFORM_BANK_UPDATED',
                'description' => 'Super Admin memperbarui rekening bank penerimaan langganan SaaS platform.',
            ]);

            return back()->with('success', 'Rekening transfer manual penerimaan platform berhasil disimpan.');
        }

        // Updating Gateway provider
        $provider = strtoupper($request->input('provider', 'DUITKU'));
        $gateways = PlatformSetting::get('platform_gateways', []);

        if ($provider === 'DUITKU') {
            $gateways['DUITKU'] = [
                'merchant_code' => $request->input('merchant_code'),
                'api_key' => $request->input('api_key'),
                'environment' => $request->input('environment', 'sandbox'),
                'is_active' => $request->boolean('is_active'),
            ];
        } elseif ($provider === 'MIDTRANS') {
            $gateways['MIDTRANS'] = [
                'server_key' => $request->input('server_key'),
                'client_key' => $request->input('client_key'),
                'environment' => $request->input('environment', 'sandbox'),
                'is_active' => $request->boolean('is_active'),
            ];
        } elseif ($provider === 'XENDIT') {
            $gateways['XENDIT'] = [
                'secret_key' => $request->input('secret_key'),
                'webhook_token' => $request->input('webhook_token'),
                'environment' => $request->input('environment', 'sandbox'),
                'is_active' => $request->boolean('is_active'),
            ];
        } elseif ($provider === 'TRIPAY') {
            $gateways['TRIPAY'] = [
                'merchant_code' => $request->input('merchant_code'),
                'api_key' => $request->input('api_key'),
                'private_key' => $request->input('private_key'),
                'environment' => $request->input('environment', 'sandbox'),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        PlatformSetting::set('platform_gateways', $gateways);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'PLATFORM_GATEWAY_UPDATED',
            'description' => "Super Admin memperbarui konfigurasi gateway platform {$provider}.",
        ]);

        return back()->with('success', "Konfigurasi Payment Gateway {$provider} Platform berhasil diperbarui.");
    }

    public function whatsapp()
    {
        $wa = PlatformSetting::get('platform_whatsapp', [
            'provider' => 'Fonnte Official / Wablas Gateway',
            'token' => '',
            'sender_number' => '',
            'is_active' => true,
        ]);

        return view('super-admin.settings.whatsapp', compact('wa'));
    }

    public function updateWhatsapp(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'string'],
            'token' => ['required', 'string'],
            'sender_number' => ['required', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        PlatformSetting::set('platform_whatsapp', $validated);

        return back()->with('success', 'Konfigurasi WhatsApp Resmi Platform berhasil disimpan.');
    }

    public function email()
    {
        $email = PlatformSetting::get('platform_email', [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@moowifi.id',
            'mail_from_name' => 'MooWiFi SaaS Platform',
        ]);

        return view('super-admin.settings.email', compact('email'));
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => ['required', 'string'],
            'mail_port' => ['required', 'numeric'],
            'mail_username' => ['required', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['required', 'string'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string'],
        ]);

        $current = PlatformSetting::get('platform_email', []);
        if (empty($validated['mail_password'])) {
            $validated['mail_password'] = $current['mail_password'] ?? '';
        }

        $validated['mail_mailer'] = 'smtp';
        PlatformSetting::set('platform_email', $validated);

        return back()->with('success', 'Konfigurasi SMTP Email Platform berhasil disimpan.');
    }

    public function logs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(25);
        return view('super-admin.settings.logs', compact('logs'));
    }

    public function announcements()
    {
        $announcements = PlatformSetting::get('platform_announcements', []);
        return view('super-admin.settings.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string'],
            'type' => ['required', 'in:info,warning,danger,success'],
        ]);

        $announcements = PlatformSetting::get('platform_announcements', []);
        array_unshift($announcements, [
            'id' => uniqid(),
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'created_at' => now()->format('d/m/Y H:i'),
        ]);

        PlatformSetting::set('platform_announcements', array_slice($announcements, 0, 20));

        return back()->with('success', 'Pengumuman platform berhasil disiarkan.');
    }

    public function support(Request $request)
    {
        $status = $request->query('status');
        $query = SupportTicket::latest();
        if ($status) {
            $query->where('status', strtoupper($status));
        }
        $tickets = $query->paginate(15);
        $counts = [
            'all' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'OPEN')->count(),
            'in_progress' => SupportTicket::where('status', 'IN_PROGRESS')->count(),
            'resolved' => SupportTicket::where('status', 'RESOLVED')->count(),
        ];

        return view('super-admin.settings.support', compact('tickets', 'counts', 'status'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:OPEN,IN_PROGRESS,RESOLVED,CLOSED'],
            'admin_reply' => ['required', 'string'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'admin_reply' => $validated['admin_reply'],
            'replied_at' => now(),
        ]);

        return back()->with('success', "Tiket #{$ticket->ticket_number} berhasil dibalas dan status diperbarui.");
    }
}
