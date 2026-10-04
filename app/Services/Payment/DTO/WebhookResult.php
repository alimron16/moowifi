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
        public string $status = 'PENDING', // SUCCESS, FAILED, PENDING, PAID
        public ?string $message = null,
        public ?string $errorMessage = null,
        public ?string $channel = null,
        public ?\DateTimeInterface $paidAt = null,
        public array $rawPayload = []
    ) {
        if ($this->errorMessage && !$this->message) {
            $this->message = $this->errorMessage;
        }
        if ($this->message && !$this->errorMessage) {
            $this->errorMessage = $this->message;
        }
    }
}
