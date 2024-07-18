<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Pix
{
     /**
     * Request to get PIX QR Code
     *
     * @param Payment $payment
     *
     */
    public static function getPixQrCode(Payment $payment)
    {
        try {
            $id = (string) $payment->reference;

            return Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'User-Agent'    => config('app.name'),
                'access_token'  => config('asaas.api_key'),
            ])
            ->get(config('asaas.api_url') . "/payments/{$id}/pixQrCode")
            ->throw();
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
