<?php

namespace Tests\Traits;

use Mockery;
use App\Services\Payment\Contracts\CustomerInterface;
use App\Services\Payment\Contracts\AttributeInterface;
use Attribute;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Trait MockPaymentGateway
 * Mock the payment gateway api behavior
 *
 * @package Tests\Traits
 */
trait MockPaymentGateway
{
    protected function mockPaymentGatewayWithSuccess(array $data)
    {
        $mockResponse = new Response(new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], json_encode($data)));

        Http::fake([
            'https://api.asaas.com/v3/customers' => $mockResponse,
        ]);

        app()->instance(CustomerInterface::class, Mockery::mock(CustomerInterface::class, [
            'create' => $mockResponse,
        ]));

        $attributeMock = Mockery::mock(AttributeInterface::class);
        $attributeMock->shouldReceive('setData')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return is_array($arg) || $arg instanceof \Illuminate\Support\Collection;
            }))
            ->andReturn(null);
        $attributeMock->shouldReceive('name')
            ->andReturn('Testing');
        $attributeMock->shouldReceive('id')
            ->andReturn('123');

        app()->instance(AttributeInterface::class, $attributeMock);
    }
}
