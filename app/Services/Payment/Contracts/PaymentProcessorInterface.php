<?php

namespace App\Services\Payment\Contracts;

interface PaymentProcessorInterface
{
    public function process(array $data);
}
