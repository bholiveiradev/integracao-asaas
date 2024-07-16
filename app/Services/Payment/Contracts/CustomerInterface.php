<?php

namespace App\Services\Payment\Contracts;

use App\Models\Client;

interface CustomerInterface
{
    public function create(Client $client);
}
