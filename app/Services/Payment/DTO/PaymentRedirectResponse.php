<?php

namespace App\Services\Payment\DTO;

class PaymentRedirectResponse
{
    public function __construct(
        public bool $success,
        public ?string $paymentUrl = null,
        public ?string $providerReference = null,
        public ?string $paymentChannel = null,
        public ?string $errorMessage = null,
        public array $rawResponse = []
    ) {}
}
