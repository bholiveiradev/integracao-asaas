<?php

namespace App\Services\Payment\Contracts;

use App\Models\Payment;

interface GatewayPaymentInterface
{
    public function pay(Payment $payment, array $data);
}
