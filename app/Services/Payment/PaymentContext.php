<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\{PaymentClientInterface, PaymentProcessorInterface};

class PaymentContext
{
    public function __construct(
        private PaymentClientInterface $client,
        private PaymentProcessorInterface $processor
    )
    {}

    public function createClient(array $data)
    {
        return $this->client->create($data);
    }

    public function process(array $data)
    {
        return $this->processor->process($data);
    }
}
