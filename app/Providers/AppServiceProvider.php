<?php

namespace App\Providers;

use App\Services\Payment\Contracts\{PaymentClientInterface, PaymentProcessorInterface};
use App\Services\Payment\Gateways\Asaas\{PaymentClient, PaymentProcessor};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        PaymentClientInterface::class => PaymentClient::class,
        PaymentProcessorInterface::class => PaymentProcessor::class,
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
