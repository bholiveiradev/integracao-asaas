<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Services\Payment\Contracts\PaymentProcessorInterface;

class PaymentProcessor extends AsaasGatewayBase implements PaymentProcessorInterface
{
    public function process(array $data)
    {}
}
