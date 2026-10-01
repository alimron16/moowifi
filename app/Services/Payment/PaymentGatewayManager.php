<?php

namespace App\Services\Payment;

use App\Services\Payment\Drivers\DuitkuGateway;
use App\Services\Payment\Drivers\MidtransGateway;
use App\Services\Payment\Drivers\TripayGateway;
use App\Services\Payment\Drivers\XenditGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    protected array $drivers = [];

    public function __construct()
    {
        $this->drivers = [
            'DUITKU' => DuitkuGateway::class,
            'MIDTRANS' => MidtransGateway::class,
            'XENDIT' => XenditGateway::class,
            'TRIPAY' => TripayGateway::class,
        ];
    }

    public function driver(string $provider): PaymentGatewayInterface
    {
        $providerUpper = strtoupper($provider);

        if (!isset($this->drivers[$providerUpper])) {
            throw new InvalidArgumentException("Payment gateway driver [{$providerUpper}] belum didukung.");
        }

        return app($this->drivers[$providerUpper]);
    }
}
