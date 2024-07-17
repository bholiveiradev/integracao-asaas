<?php

namespace App\Listeners;

use App\Enums\BillingType;
use App\Events\PaymentCreated;
use App\Factories\PaymentProcessorFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePaymentOnGateway implements ShouldQueue
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
        $data           = $event->data;
        $payment        = $event->payment;

        $billingType    = BillingType::from($data['billing_type']);
        $paymentGateway = PaymentProcessorFactory::create($billingType);

        $paymentGateway->process($payment, $data);
    }
}
