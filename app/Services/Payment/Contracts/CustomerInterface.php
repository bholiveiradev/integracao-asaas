<?php

namespace App\Services\Payment\Contracts;

use App\Models\Client;

interface CustomerInterface
{
    /**
     * Create a new customer
     *
     * @param Client $client
     *
     * @return void
     */
    public function create(Client $client): void;
}
