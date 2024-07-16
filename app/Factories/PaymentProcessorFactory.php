<?php

namespace App\Factories;

use App\Services\Payment\Contracts\{
    ChargeInterface,
    CreditCardInterface,
};
use App\Enums\BillingType;

class PaymentProcessorFactory
{
    public static function create(BillingType $method)
    {
        return match($method) {
            BillingType::PIX,
            BillingType::BOLETO       => app()->make(ChargeInterface::class),
            BillingType::CREDIT_CARD  => app()->make(CreditCardInterface::class),
        };
    }
}
