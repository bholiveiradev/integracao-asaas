<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use App\Services\Payment\Contracts\{GatewayBoletoMethodInterface, GatewayPaymentInterface};
use Illuminate\Support\Facades\{Http, Log};
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class BoletoPaymentMethod implements GatewayBoletoMethodInterface, GatewayPaymentInterface
{
    public function pay(Payment $payment, array $data): void
    {
        try{
            $client = auth()->user()->client;

            $settings = $client->paymentGatewaySettings()
                ->where('name', 'Asaas')
                ->first();

            if (! $settings) { return; }

            $data['customer'] = $settings->gateway_client_id;

            $response = $this->request($data);

            if ($response->ok()) {
                $this->updatePayment($payment, $response->collect());
            }
        } catch (RequestException $e) {
            Log::error($e->getMessage(), [
                'user'      => auth()->user()->email,
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'status'    => $e->response->status(),
                'trace'     => $e->getTrace(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), [
                'user'      => auth()->user()->email,
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
                'customer'      => $data['customer'],
                'billingType'   => $data['billing_type'],
                'value'         => $data['amount'],
                'dueDate'       => $data['due_date'],
                'description'   => $data['description'],
            ])->throw();
    }

    private function updatePayment(Payment $payment, Collection $data)
    {
        $payment->gateway_name  = 'Asaas';
        $payment->reference     = $data['id'];
        $payment->external_url  = $data['bankSlipUrl'];
        $payment->save();
    }
}
