<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use App\Services\Payment\Contracts\GatewayCustomerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateClientOnPaymentGateway implements ShouldQueue
{
    public $tries = 1;

    /**
     * Create the event listener.
     */
    public function __construct(
        protected GatewayCustomerInterface $gatewayCustomer,
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClientCreated $event): void
    {
        $client = $event->client;

        $this->gatewayCustomer->create($client);
    }
}
