<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateClientOnGateway implements ShouldQueue
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
    public function handle(ClientCreated $event): void
    {
        $client = $event->client;

        $this->gatewayCustomer->create($client);
    }
}
