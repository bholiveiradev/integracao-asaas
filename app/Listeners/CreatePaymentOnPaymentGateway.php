<?php

namespace App\Listeners;

use App\Enums\BillingType;
use App\Events\PaymentCreated;
use App\Factories\PaymentGatewayFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreatePaymentOnPaymentGateway
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentCreated $event): void
    {
        $data = $event->data;
        $payment = $event->payment;

        $billingType = BillingType::from($data['billing_type']);

        $paymentGateway = PaymentGatewayFactory::create($billingType);

        $paymentGateway->pay($payment, $data);
    }
}
