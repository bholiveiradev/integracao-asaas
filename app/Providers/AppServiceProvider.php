<?php

namespace App\Providers;

use App\Services\Payment\Contracts\{CustomerInterface, ChargeInterface,CreditCardInterface, GatewayPixMethodInterface};
use App\Services\Payment\Gateways\Asaas\{Customer, ChargeProcessor, CreditCardProcessor, PixPaymentMethod};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        CustomerInterface::class     => Customer::class,
        ChargeInterface::class       => ChargeProcessor::class,
        CreditCardInterface::class   => CreditCardProcessor::class,
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
