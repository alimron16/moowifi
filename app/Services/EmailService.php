<?php

namespace App\Services;

use App\Mail\InvoiceEmailMailable;
use App\Mail\TestEmailMailable;
use App\Models\Invoice;
use App\Models\NotificationLog;
use App\Models\Tenant;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Dynamically configure Laravel mailer with tenant's Gmail SMTP
     */
    protected function configureTenantMailer(Tenant $tenant): bool
    {
        $username = $tenant->getSetting('mail.username');
        $password = $tenant->getSetting('mail.password');

        if (empty($username) || empty($password)) {
            return false;
        }

        $host = $tenant->getSetting('mail.host', 'smtp.gmail.com');
        $port = (int) $tenant->getSetting('mail.port', 587);
        $encryption = $tenant->getSetting('mail.encryption', 'tls');
        $fromAddress = $tenant->getSetting('mail.from_address', $username);
        $fromName = $tenant->getSetting('mail.from_name', $tenant->name);

        Config::set('mail.mailers.tenant_smtp', [
            'transport' => 'smtp',
            'host' => $host,
            'port' => $port,
            'encryption' => $encryption,
            'username' => $username,
            'password' => $password,
            'timeout' => 10,
        ]);

        Config::set('mail.from.address', $fromAddress);
        Config::set('mail.from.name', $fromName);

        return true;
    }

    /**
     * Send test email to verify Gmail credentials
     */
    public function sendTestEmail(Tenant $tenant, string $recipient): array
    {
        if (!$this->configureTenantMailer($tenant)) {
            return [
                'success' => false,
                'message' => 'Alamat Gmail dan App Password belum dikonfigurasi lengkap.',
            ];
        }

        try {
            Mail::mailer('tenant_smtp')->to($recipient)->send(new TestEmailMailable($tenant));

            return [
                'success' => true,
                'message' => 'Email uji coba berhasil dikirim ke ' . $recipient,
            ];
        } catch (Exception $e) {
            Log::error('Tenant Gmail test failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send invoice email with PDF attachment
     */
    public function sendInvoice(Invoice $invoice): array
    {
        $tenant = $invoice->tenant;
        $customer = $invoice->customer;

        if (empty($customer->email)) {
            return ['success' => false, 'message' => 'Pelanggan tidak memiliki alamat email.'];
        }

        if (!$this->configureTenantMailer($tenant)) {
            return ['success' => false, 'message' => 'Konfigurasi Gmail tenant belum aktif.'];
        }

        try {
            Mail::mailer('tenant_smtp')->to($customer->email)->send(new InvoiceEmailMailable($invoice));

            NotificationLog::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'invoice_id' => $invoice->id,
                'channel' => 'EMAIL',
                'recipient' => $customer->email,
                'subject' => "Faktur Tagihan {$invoice->invoice_number}",
                'message' => "Pengiriman tagihan periode {$invoice->period_start->format('d/m/Y')} dengan lampiran PDF.",
                'provider' => 'GMAIL_SMTP',
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            return ['success' => true, 'message' => 'Faktur berhasil dikirim ke ' . $customer->email];
        } catch (Exception $e) {
            Log::error('Invoice email failed: ' . $e->getMessage());

            NotificationLog::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'invoice_id' => $invoice->id,
                'channel' => 'EMAIL',
                'recipient' => $customer->email,
                'subject' => "Faktur Tagihan {$invoice->invoice_number}",
                'message' => 'Gagal mengirim invoice email: ' . $e->getMessage(),
                'provider' => 'GMAIL_SMTP',
                'status' => 'FAILED',
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Gagal kirim email: ' . $e->getMessage()];
        }
    }
}
