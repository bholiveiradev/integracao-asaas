<?php

namespace App\Providers;

use App\Services\Payment\Contracts\{GatewayCustomerInterface, GatewayBoletoMethodInterface,GatewayCreditCardMethodInterface, GatewayPixMethodInterface};
use App\Services\Payment\Gateways\Asaas\{Customer, BoletoPaymentMethod, CreditCardPaymentMethod, PixPaymentMethod};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        GatewayCustomerInterface::class            => Customer::class,
        GatewayBoletoMethodInterface::class        => BoletoPaymentMethod::class,
        GatewayCreditCardMethodInterface::class    => CreditCardPaymentMethod::class,
        GatewayPixMethodInterface::class           => PixPaymentMethod::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
