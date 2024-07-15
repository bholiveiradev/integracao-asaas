<?php

namespace App\Services\Payment\Contracts;

use App\Models\Client;

interface GatewayCustomerInterface
{
    public function create(Client $client);
}
