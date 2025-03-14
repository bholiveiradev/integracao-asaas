<?php

namespace App\Http\Controllers\API\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\{Payment, PaymentGatewaySetting};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AsaasPaymentWebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            Log::debug('Asaas Payment Webhook Request: ', ['headers' => $request->header(), 'data' => $request->all()]);
            $signature = $request->header('asaas-access-token');
            if ($signature !== config('asaas.webhook_signature')) {
                abort(Response::HTTP_FORBIDDEN, 'Unauthorized.');
            }
            return $next($request);
        });
    }

    public function handle(Request $request): void
    {
        switch ($request->event) {
            case 'PAYMENT_CREATED':
                self::create($request);
                break;
            case 'PAYMENT_CONFIRMED':
            case 'PAYMENT_RECEIVED':
                self::updatePaid($request);
                break;
            case 'PAYMENT_DELETED':
                self::delete($request);
                break;
        }
    }

    private static function create(Request $request): void
    {
        $payment = Payment::where('reference', $request->payment['id'])->first();

        if ($payment) { return; }

        $customer = PaymentGatewaySetting::where(
            'gateway_customer_id', $request->payment['customer']
        )->first()->customer;

        $customer->payments()->create([
            'gateway_name'      => 'ASAAS',
            'reference'         => $request->payment['id'],
            'description'       => $request->payment['description'],
            'amount'            => $request->payment['value'],
            'billing_type'      => $request->payment['billingType'],
            'status'            => $request->payment['status'],
            'due_date'          => $request->payment['dueDate'],
            'installment_count' => $request->payment['installmentNumber'],
            'paid_at'           => $request->payment['confirmedDate'],
            'external_url'      => $request->payment['bankSlipUrl'],
            'processing'        => false,
        ]);
    }

    private static function updatePaid(Request $request): void
    {
        $payment = Payment::where('reference', $request->payment['id'])->first();

        if (! $payment) { return; }

        $payment->description       = $request->payment['description'];
        $payment->amount            = $request->payment['value'];
        $payment->status            = $request->payment['status'];
        $payment->due_date          = $request->payment['dueDate'];
        $payment->installment_count = $request->payment['installmentNumber'];
        $payment->paid_at           = $request->payment['confirmedDate'];
        $payment->external_url      = $request->payment['bankSlipUrl'];
        $payment->processing        = false;
        $payment->save();
    }

    private static function delete(Request $request): void
    {
        $payment = Payment::where('reference', $request->payment['id'])->first();

        if (! $payment) { return; }
        
        $payment->delete();
    }
}
