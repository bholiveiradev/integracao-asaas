<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCustomerOnGateway implements ShouldQueue
{
    public $tries = 1;

    /**
     * Create the event listener.
     */
    public function __construct(
        protected CustomerInterface $gatewayCustomer,
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CustomerCreated $event): void
    {
        $this->gatewayCustomer->create($event->customer);
    }
}
