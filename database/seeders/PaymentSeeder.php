<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::all()->each(function (Customer $customer) {
            $billingTypes = ['PIX', 'BOLETO', 'CREDIT_CARD'];
            $statuses = ['PENDING', 'PAID', 'FAILED', 'CANCELLED', 'REFUNDED'];

            for ($i = 0; $i < 2; $i++) {
                Payment::factory()->create([
                    'customer_id'   => $customer->id,
                    'reference'     => Str::uuid(),
                    'amount'        => rand(1, 999),
                    'billing_type'  => $billingTypes[array_rand($billingTypes)],
                    'status'        => $statuses[array_rand($statuses)],
                    'external_url'  => 'http://example.com/external/payment',
                    'gateway_name'  => 'Seeder',
                    'processing'    => false,
                ]);
            }
        });
    }
}
