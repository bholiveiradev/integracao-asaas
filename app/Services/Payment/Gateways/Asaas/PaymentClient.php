<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Services\Payment\Contracts\PaymentClientInterface;

class PaymentClient extends AsaasGatewayBase implements PaymentClientInterface
{
    public function create(array $data)
    {}
}
