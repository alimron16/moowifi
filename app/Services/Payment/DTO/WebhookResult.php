<?php

namespace App\Services\Payment\DTO;

class WebhookResult
{
    public function __construct(
        public bool $isValid,
        public ?string $provider = null,
        public ?string $merchantOrderId = null,
        public ?string $providerReference = null,
        public ?float $amount = null,
        public string $status = 'PENDING', // SUCCESS, FAILED, PENDING
        public ?string $message = null,
        public array $rawPayload = []
    ) {}
}
