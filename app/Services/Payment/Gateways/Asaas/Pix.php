<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class Pix
{
    public function __construct(
        private HttpClient $httpClient
    ) {
        if (app()->environment('testing')) { return; }
    }

     /**
     * Request to get PIX QR Code
     *
     * @param Payment $payment
     *
     */
    public function getPixQrCode(Payment $payment)
    {
        try {
            $id = (string) $payment->reference;

            return $this->httpClient->get("/payments/{$id}/pixQrCode");
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
}
