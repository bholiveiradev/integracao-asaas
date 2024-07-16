<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Client;
use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class Customer implements CustomerInterface
{
    public function create(Client $client): void
    {
        try {
            $response = $this->request($client);

            $client->paymentGatewaySettings()
                ->create([
                    'name'              => 'Asaas',
                    'gateway_client_id' => $response->collect()['id'],
                ]);
        } catch (RequestException $e) {
            Log::error($e->getMessage(), [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'status'    => $e->response->status(),
                'trace'     => $e->getTrace(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'trace'     => $e->getTrace(),
            ]);
        }
    }

    /**
     * Request to create a new customer
     *
     * @param Client $client
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private function request(Client $client)
    {
        return Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'User-Agent'    => config('app.name'),
                'access_token'  => config('asaas.api_key'),
            ])->post(config('asaas.api_url') . '/customers', [
                'name'          => $client->user->name,
                'cpfCnpj'       => $client->cpf_cnpj,
                'email'         => $client->email,
                'phone'         => $client->phone,
                'mobilePhone'   => $client->mobile_phone
            ])->throw();
    }
}
