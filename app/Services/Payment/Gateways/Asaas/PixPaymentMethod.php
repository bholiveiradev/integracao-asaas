<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use App\Services\Payment\Contracts\{GatewayPaymentInterface, GatewayPixMethodInterface};

class PixPaymentMethod implements GatewayPixMethodInterface, GatewayPaymentInterface
{
    public function pay(Payment $payment, array $data)
    {
        return $this;
    }

    private function request(array $data)
    {

    }
}
