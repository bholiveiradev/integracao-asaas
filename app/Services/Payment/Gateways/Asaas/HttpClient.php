<?php

namespace App\Services\Payment\Gateways\Asaas;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class HttpClient
{
    public function __construct(
        protected PendingRequest $httpClient
    ) {
        $this->httpClient = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'User-Agent'    => config('app.name'),
            'access_token'  => config('asaas.api_key'),
        ]);
    }

    public function post(string $resource, array $body = [])
    {
        return $this->httpClient->post(
                config('asaas.api_url') . '/' . ltrim($resource, '/'),
                $body
            )->throw();
    }

    public function get(string $resource, array $params = [])
    {
        return $this->httpClient->get(
                config('asaas.api_url') . '/' . ltrim($resource, '/'),
                $params
            )->throw();
    }
}
