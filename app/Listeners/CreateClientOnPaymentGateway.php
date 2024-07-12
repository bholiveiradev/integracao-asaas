<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use App\Services\Payment\Contracts\AttributeInterface;
use App\Services\Payment\Contracts\CustomerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateClientOnPaymentGateway implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected CustomerInterface $customerPayment,
        private AttributeInterface $attribute,
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

        $response = $this->customerPayment->create(
            name: $client->user->name,
            cpfCnpj: $client->cpf_cnpj,
            email: $client->email,
            phone: $client->phone,
            mobilePhone: $client->mobile_phone
        );

        if ($response->ok()) {
            $client->paymentGatewaySettings()
                ->create([
                    'name'              => $this->attribute->name(),
                    'gateway_client_id' => $this->attribute->id(),
                ]);
        }
    }
}
