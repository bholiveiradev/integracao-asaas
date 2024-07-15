<?php

namespace Database\Seeders;

use App\Models\Client;
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
        Client::all()->each(function($client) {
            $billingTypes = ['PIX', 'BOLETO', 'CREDIT_CARD'];
            $statuses = ['PENDING', 'PAID', 'FAILED', 'CANCELLED', 'REFUNDED'];

            for ($i = 0; $i < 10; $i++) {
                Payment::factory()->create([
                    'client_id'     => $client->id,
                    'reference'     => Str::uuid(),
                    'amount'        => rand(1, 999999999999),
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
