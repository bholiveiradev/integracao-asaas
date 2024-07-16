<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use App\Services\Payment\Contracts\{CreditCardInterface, ProcessorInterface};
use Illuminate\Support\Facades\{Http, Log};
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class CreditCardProcessor implements CreditCardInterface, ProcessorInterface
{
    public function pay(Payment $payment, array $data): void
    {
        try{
            $settings = $payment->client
                ->paymentGatewaySettings()
                ->where('name', 'Asaas')
                ->first();

            if (! $settings) { return; }

            $data['customer'] = $settings->gateway_client_id;

            $response = $this->request($data);

            $this->updatePayment($payment, $response->collect());
        } catch (RequestException $e) {
            $payment->gateway_name  = 'Asaas';
            $payment->status        = 'REQUEST_ERROR';
            $payment->processing    = false;
            $payment->save();

            Log::error($e->getMessage(), [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'status'    => $e->response->status(),
                'trace'     => $e->getTrace(),
            ]);
        } catch (\Throwable $e) {
            $payment->gateway_name  = 'Asaas';
            $payment->status        = 'INTERNAL_ERROR';
            $payment->processing    = false;
            $payment->save();

            Log::error($e->getMessage(), [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'trace'     => $e->getTrace(),
            ]);
        }
    }

    private function request(array $data)
    {
        return Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'User-Agent'    => config('app.name'),
                'access_token'  => config('asaas.api_key'),
            ])->post(config('asaas.api_url') . '/payments',[
                'customer'              => $data['customer'],
                'billingType'           => $data['billing_type'],
                'value'                 => $data['amount'],
                'dueDate'               => $data['due_date'],
                'description'           => $data['description'],
                'installmentCount'      => $data['installment_count'],
                'installmentValue'      => $data['installment_value'],
                'creditCard'            => [
                    'holderName'            => $data['card_holder_name'],
                    'number'                => $data['card_number'],
                    'expiryMonth'           => $data['card_expiration_month'],
                    'expiryYear'            => $data['card_expiration_year'],
                    'ccv'                   => $data['cvv'],
                ],
                'creditCardHolderInfo'  => [
                    'name'                  => $data['name'],
                    'email'                 => $data['email'],
                    'cpfCnpj'               => $data['cpf_cnpj'],
                    'postalCode'            => $data['postal_code'],
                    'addressNumber'         => $data['address_number'],
                    'addressComplement'     => $data['address_complement'],
                    'phone'                 => $data['phone'],
                    'mobilePhone'           => $data['mobile_phone'],
                ],
                'remoteIp'              => $data['remote_ip']
            ])->throw();
    }

    private function updatePayment(Payment $payment, Collection $data)
    {
        $payment->gateway_name  = 'Asaas';
        $payment->reference     = $data['id'];
        $payment->external_url  = $data['bankSlipUrl'];
        $payment->processing    = false;
        $payment->save();
    }
}
