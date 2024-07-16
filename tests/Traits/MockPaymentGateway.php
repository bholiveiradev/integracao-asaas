<?php

namespace Tests\Traits;

use App\Models\Client;
use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Mockery;

/**
 * Trait MockPaymentGateway
 * Mock the payment gateway api behavior
 *
 * @package Tests\Traits
 */
trait MockPaymentGateway
{
    protected function mockPaymentGatewayCustomer()
    {
        app()->instance(CustomerInterface::class, Mockery::mock(CustomerInterface::class, [
            'create' => function(Client $client) {
                $data = [
                    'name'          => $client->user->name,
                    'cpfCnpj'       => $client->cpf_cnpj,
                    'email'         => $client->email,
                    'phone'         => $client->phone,
                    'mobilePhone'   => $client->mobile_phone
                ];

                $mockResponse = new Response(new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], json_encode($data)));

                return $mockResponse;
            },
        ]));
    }

    protected function mockCreateCustomerOnPaymentGateway(Client $client)
    {
        $data = [
            'name'          => $client->user->name,
            'cpfCnpj'       => $client->cpf_cnpj,
            'email'         => $client->email,
            'phone'         => $client->phone,
            'mobilePhone'   => $client->mobile_phone
        ];

        $mockResponse = new Response(new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], json_encode($data)));

        Http::fake([
            'https://api.asaas.com/v3/customers' => $mockResponse,
        ]);

        $client->paymentGatewaySettings()->create([
            'name'              => 'Testing',
            'gateway_client_id' => '123'
        ]);
    }
}
