<?php

namespace Tests\Traits;

use App\Models\Customer;
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
            'create' => function(Customer $customer) {
                $data = [
                    'name'          => $customer->user->name,
                    'cpfCnpj'       => $customer->cpf_cnpj,
                    'email'         => $customer->email,
                    'phone'         => $customer->phone,
                    'mobilePhone'   => $customer->mobile_phone
                ];

                $mockResponse = new Response(new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], json_encode($data)));

                return $mockResponse;
            },
        ]));
    }

    protected function mockCreateCustomerOnPaymentGateway(Customer $customer)
    {
        $data = [
            'name'          => $customer->user->name,
            'cpfCnpj'       => $customer->cpf_cnpj,
            'email'         => $customer->email,
            'phone'         => $customer->phone,
            'mobilePhone'   => $customer->mobile_phone
        ];

        $mockResponse = new Response(new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], json_encode($data)));

        Http::fake([
            config('asaas.api_url') . '/customers' => $mockResponse,
        ]);

        $customer->paymentGatewaySettings()->create([
            'name'                => 'Testing',
            'gateway_customer_id' => '123'
        ]);
    }
}
