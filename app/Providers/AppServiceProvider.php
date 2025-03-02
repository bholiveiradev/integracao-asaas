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
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
