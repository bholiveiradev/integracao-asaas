<?php

namespace App\Services\Payment\Gateways\Asaas;

abstract class AsaasGatewayBase
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('asaas.api_key');
        $this->apiUrl = config('asaas.api_url');
    }

    public function get(string $path, array $data = [], array $headers = [])
    {}

    public function post(string $path, array $data = [], array $headers = [])
    {}
}
