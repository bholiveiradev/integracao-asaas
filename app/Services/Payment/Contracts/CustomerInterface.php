<?php

namespace App\Services\Payment\Contracts;

use App\Models\Customer as AppCustomer;

interface CustomerInterface
{
    /**
     * Create a new customer
     *
     * @param AppCustomer $customer
     *
     * @return void
     */
    public function create(AppCustomer $customer): void;
}
