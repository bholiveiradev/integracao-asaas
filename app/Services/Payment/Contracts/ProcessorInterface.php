<?php

namespace App\Services\Payment\Contracts;

use App\Models\Payment;

interface ProcessorInterface
{
    public function pay(Payment $payment, array $data);
}
