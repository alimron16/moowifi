<?php

namespace App\Services\WhatsApp;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\NotificationLog;
use App\Models\Tenant;
use App\Services\WhatsApp\Drivers\FonnteDriver;
use App\Services\WhatsApp\Drivers\QrConnectDriver;

class WhatsAppService
{
    /**
     * Send direct message using tenant's chosen connection method
     */
    public function sendMessage(Tenant $tenant, string $recipient, string $message): array
    {
        $connType = $tenant->getSetting('whatsapp.type', 'API');

        if ($connType === 'QR') {
            $driver = new QrConnectDriver();
            return $driver->sendMessage($tenant, $recipient, $message);
        }

        // Default: Opsi 1 Third-Party API Gateway (Fonnte)
        $token = $tenant->getSetting('whatsapp.token', '');
        $driver = new FonnteDriver($token);
        return $driver->sendMessage($recipient, $message);
    }

    /**
     * Send notification to customer and record to NotificationLog
     */
    public function sendToCustomer(
        Tenant $tenant,
        Customer $customer,
        string $message,
        ?Invoice $invoice = null
    ): NotificationLog {
        $connType = $tenant->getSetting('whatsapp.type', 'API');
        $providerName = $connType === 'QR' ? 'BAILEYS_QR' : 'FONNTE';

        $result = $this->sendMessage($tenant, $customer->phone, $message);

        return NotificationLog::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'invoice_id' => $invoice?->id,
            'channel' => 'WHATSAPP',
            'recipient' => $customer->phone,
            'message' => $message,
            'provider' => $providerName,
            'provider_message_id' => $result['message_id'] ?? null,
            'status' => $result['success'] ? 'SENT' : 'FAILED',
            'error' => $result['error'] ?? ($result['success'] ? null : ($result['message'] ?? 'Gagal')),
            'sent_at' => $result['success'] ? now() : null,
        ]);
    }
}
