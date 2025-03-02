<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Customer as AppCustomer;
use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class Customer implements CustomerInterface
{
    public function __construct(
        private HttpClient $httpClient
    ) {
        if (app()->environment('testing')) { return; }
    }

     /**
     * Create a new customer
     *
     * @param AppCustomer $customer
     *
     * @return void
     */
    public function create(AppCustomer $customer): void
    {
        try {
            $response = $this->request($customer);

            $customer->paymentGatewaySettings()
                ->create([
                    'name'                => 'ASAAS',
                    'gateway_customer_id' => $response->collect()['id'],
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
     * @param AppCustomer $customer
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Illuminate\Http\Client\RequestException
     */
    private function request(AppCustomer $customer): Response
    {
        return $this->httpClient->post('/customers', [
                'name'          => $customer->user->name,
                'cpfCnpj'       => $customer->cpf_cnpj,
                'email'         => $customer->email,
                'phone'         => $customer->phone,
                'mobilePhone'   => $customer->mobile_phone
            ]);
    }
}
