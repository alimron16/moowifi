<?php

namespace App\Services\WhatsApp;

interface WhatsAppServiceInterface
{
    /**
     * Send WhatsApp message to recipient phone number
     */
    public function sendMessage(string $recipient, string $message, array $options = []): array;
}
