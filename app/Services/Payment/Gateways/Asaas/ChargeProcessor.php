<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use App\Services\Payment\Contracts\{ChargeInterface, ProcessorInterface};
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class ChargeProcessor implements ChargeInterface, ProcessorInterface
{
    public function __construct(
        private HttpClient $httpClient
    ) {
        if (app()->environment('testing')) { return; }
    }

    /**
     * Process the payment
     *
     * @param Payment $payment
     * @param array $data
     *
     * @return void
     */
    public function process(Payment $payment, array $data): void
    {
        try{
            $settings = $payment->customer
                ->paymentGatewaySettings()
                ->where('name', 'ASAAS')
                ->first();

            if (! $settings) { return; }

            $data['customer'] = $settings->gateway_customer_id;

            $response = $this->request($data);

            $this->updatePayment($payment, $response->collect());
        } catch (RequestException $e) {
            $this->updatePayment($payment, [
                'status' => 'REQUEST_ERROR',
            ]);

            Log::error($e->getMessage(), [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'status'    => $e->response->status(),
                'trace'     => $e->getTrace(),
            ]);
        } catch (\Throwable $e) {
            $this->updatePayment($payment, [
                'status' => 'INTERNAL_ERROR',
            ]);

            Log::error($e->getMessage(), [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'trace'     => $e->getTrace(),
            ]);
        }
    }

    /**
     * Request to create a new payment
     *
     * @param array $data
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Illuminate\Http\Client\RequestException
     */
    private function request(array $data): Response
    {
        return $this->httpClient->post('/payments', [
                'customer'      => $data['customer'],
                'billingType'   => $data['billing_type'],
                'value'         => $data['amount'],
                'dueDate'       => $data['due_date'],
                'description'   => $data['description'],
            ]);
    }

     /**
     * Update payment
     *
     * @param Payment $payment
     * @param Collection|array $data
     *
     * @return void
     */
    private function updatePayment(Payment $payment, Collection|array $data): void
    {
        $payment->gateway_name  = 'ASAAS';
        $payment->reference     = $data['id'] ?? null;
        $payment->external_url  = $data['bankSlipUrl'] ?? null;
        $payment->status        = $data['status'];
        $payment->processing    = $data['processing'] ?? false;
        $payment->save();
    }
}
