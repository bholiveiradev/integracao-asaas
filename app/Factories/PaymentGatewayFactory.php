<?php

namespace App\Factories;

use App\Services\Payment\Contracts\{
    GatewayBoletoMethodInterface,
    GatewayCreditCardMethodInterface,
    GatewayPixMethodInterface
};
use App\Enums\BillingType;

class PaymentGatewayFactory
{
    public static function create(BillingType $method)
    {
        return match($method) {
            BillingType::PIX          => app()->make(GatewayPixMethodInterface::class),
            BillingType::BOLETO       => app()->make(GatewayBoletoMethodInterface::class),
            BillingType::CREDIT_CARD  => app()->make(GatewayCreditCardMethodInterface::class),
        };
    }
}
