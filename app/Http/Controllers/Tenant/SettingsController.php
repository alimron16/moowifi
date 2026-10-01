<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\EmailService;
use App\Services\WhatsApp\Drivers\FonnteDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct(protected EmailService $emailService)
    {
    }

    public function index(Request $request)
    {
        $tenant = Auth::user()->tenant;
        $users = User::where('tenant_id', $tenant->id)->get();
        $gateways = PaymentMethod::where('tenant_id', $tenant->id)
            ->where('type', 'GATEWAY')
            ->get()
            ->keyBy('provider');

        $waSettings = $tenant->getSetting('whatsapp', []);
        $activeTab = $request->get('tab', 'business');

        return view('tenant.settings.index', compact('tenant', 'users', 'gateways', 'waSettings', 'activeTab'));
    }

    public function updateBusiness(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
        ]);

        $tenant = Auth::user()->tenant;
        $tenant->update($request->only('name', 'phone', 'email', 'address'));

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'event' => 'BUSINESS_PROFILE_UPDATED',
            'description' => 'Memperbarui profil bisnis RT/RW Net.',
        ]);

        return redirect()->route('tenant.settings.index', ['tab' => 'business'])->with('success', 'Profil bisnis berhasil diperbarui.');
    }

    public function updateBillingPolicy(Request $request)
    {
        $request->validate([
            'default_billing_day' => ['required', 'integer', 'min:1', 'max:28'],
            'default_due_day' => ['required', 'integer', 'min:1', 'max:28'],
            'default_grace_period_days' => ['required', 'integer', 'min:0', 'max:30'],
            'auto_cut_hour' => ['required', 'string', 'max:5'],
            'isolation_profile' => ['required', 'string', 'max:50'],
            'auto_cut_enabled' => ['nullable', 'boolean'],
        ]);

        $tenant = Auth::user()->tenant;
        $settings = $tenant->settings ?? [];

        $settings['billing'] = [
            'default_billing_day' => (int) $request->default_billing_day,
            'default_due_day' => (int) $request->default_due_day,
            'default_grace_period_days' => (int) $request->default_grace_period_days,
            'auto_cut_hour' => $request->auto_cut_hour,
            'isolation_profile' => $request->isolation_profile,
            'auto_cut_enabled' => $request->boolean('auto_cut_enabled'),
        ];

        $tenant->update(['settings' => $settings]);

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'event' => 'BILLING_POLICY_UPDATED',
            'description' => 'Memperbarui kebijakan billing & jadwal auto-cut isolir.',
        ]);

        return redirect()->route('tenant.settings.index', ['tab' => 'billing'])->with('success', 'Kebijakan billing & auto-cut berhasil disimpan.');
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'gmail_username' => ['required', 'email'],
            'gmail_password' => ['nullable', 'string', 'max:100'],
            'from_name' => ['required', 'string', 'max:100'],
        ]);

        $tenant = Auth::user()->tenant;
        $settings = $tenant->settings ?? [];

        $settings['mail']['username'] = $request->gmail_username;
        $settings['mail']['from_address'] = $request->gmail_username;
        $settings['mail']['from_name'] = $request->from_name;
        $settings['mail']['host'] = 'smtp.gmail.com';
        $settings['mail']['port'] = 587;
        $settings['mail']['encryption'] = 'tls';

        if ($request->filled('gmail_password')) {
            // Remove spaces from 16-character Google App Password
            $password = str_replace(' ', '', $request->gmail_password);
            $settings['mail']['password'] = $password;
        }

        $tenant->update(['settings' => $settings]);

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::id(),
            'event' => 'EMAIL_SETTINGS_UPDATED',
            'description' => 'Memperbarui konfigurasi akun Gmail SMTP untuk pengiriman tagihan.',
        ]);

        return redirect()->route('tenant.settings.index', ['tab' => 'email'])->with('success', 'Pengaturan Gmail SMTP berhasil disimpan.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_recipient' => ['required', 'email'],
        ]);

        $tenant = Auth::user()->tenant;
        $result = $this->emailService->sendTestEmail($tenant, $request->test_recipient);

        if ($result['success']) {
            return redirect()->route('tenant.settings.index', ['tab' => 'email'])->with('success', $result['message']);
        }

        return redirect()->route('tenant.settings.index', ['tab' => 'email'])->with('error', $result['message']);
    }

    public function updateTemplates(Request $request)
    {
        $tenant = Auth::user()->tenant;
        $settings = $tenant->settings ?? [];

        $settings['templates'] = [
            'wa_invoice' => $request->input('wa_invoice'),
            'wa_reminder' => $request->input('wa_reminder'),
            'wa_isolation' => $request->input('wa_isolation'),
            'wa_receipt' => $request->input('wa_receipt'),
        ];

        $tenant->update(['settings' => $settings]);

        return redirect()->route('tenant.settings.index', ['tab' => 'templates'])->with('success', 'Template pesan notifikasi berhasil disimpan.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:ADMIN,FINANCE,TECHNICIAN'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $tenant = Auth::user()->tenant;

        User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 'ACTIVE',
        ]);

        return redirect()->route('tenant.settings.index', ['tab' => 'users'])->with('success', 'Staf baru berhasil ditambahkan.');
    }
}
