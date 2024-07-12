<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Customer implements CustomerInterface
{
    public function create(
        string  $name,
        string  $cpfCnpj,
        ?string $email,
        ?string $phone,
        ?string $mobilePhone,
    ): Response
    {
        return Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'User-Agent'    => config('app.name'),
                'access_token'  => config('asaas.api_key'),
            ])
            ->post(config('asaas.api_url') . '/customers', [
                'name'          => $name,
                'cpfCnpj'       => $cpfCnpj,
                'email'         => $email,
                'phone'         => $phone,
                'mobilePhone'   => $mobilePhone
            ]);
    }
}
