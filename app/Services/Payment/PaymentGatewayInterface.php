<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Services\Payment\DTO\PaymentRedirectResponse;
use App\Services\Payment\DTO\WebhookResult;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create payment / charge on provider
     */
    public function createPayment(Invoice $invoice, PaymentMethod $method, array $options = []): PaymentRedirectResponse;

    /**
     * Verify incoming webhook authenticity
     */
    public function verifyWebhook(Request $request, PaymentMethod $method): bool;

    /**
     * Parse and normalize webhook payload
     */
    public function handleWebhook(Request $request, PaymentMethod $method): WebhookResult;
}
