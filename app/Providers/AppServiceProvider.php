<?php

namespace App\Providers;

use App\Services\Payment\Contracts\{AttributeInterface, CustomerInterface, ProcessorInterface, SettingsInterface};
use App\Services\Payment\Gateways\Asaas\{Attribute, Customer, Processor};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        CustomerInterface::class    => Customer::class,
        ProcessorInterface::class   => Processor::class,
        AttributeInterface::class   => Attribute::class,
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
