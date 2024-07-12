<?php

namespace App\Services\Payment\Contracts;

use Illuminate\Http\Client\Response;

interface CustomerInterface
{
    public function create(
        string $name,
        string $cpfCnpj,
        ?string $email,
        ?string $phone,
        ?string $mobilePhone
    ): Response;
}
