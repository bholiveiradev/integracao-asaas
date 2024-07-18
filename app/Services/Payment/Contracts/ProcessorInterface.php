<?php

namespace App\Services\Payment\Contracts;

use App\Models\Payment;

interface ProcessorInterface
{
    /**
     * Process the payment
     *
     * @param Payment $payment
     * @param array $data
     *
     * @return void
     */
    public function process(Payment $payment, array $data): void;
}
