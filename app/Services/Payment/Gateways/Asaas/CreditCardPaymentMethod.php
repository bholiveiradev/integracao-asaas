<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use App\Services\Payment\Contracts\{GatewayCreditCardMethodInterface, GatewayPaymentInterface};

class CreditCardPaymentMethod implements GatewayCreditCardMethodInterface, GatewayPaymentInterface
{
    public function pay(Payment $payment, array $data)
    {
        return $this;
    }

    public function request(array $data)
    {
        return $this;
    }
}
