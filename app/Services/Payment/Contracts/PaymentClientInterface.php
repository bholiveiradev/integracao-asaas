<?php

namespace App\Services\Payment\Contracts;

interface PaymentClientInterface
{
    public function create(array $data);
}
