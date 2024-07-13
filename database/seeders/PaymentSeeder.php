<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::factory(20)->create([
            'client_id'     => Client::all()->random()->id,
            'reference'     => null,
            'amount'        => rand(1, 1000),
            'billing_type'  => array_rand(['PIX', 'BOLETO', 'CREDIT_CARD']),
            'status'        => array_rand(['pending', 'paid', 'failed', 'cancelled', 'refunded']),
            'external_url'  => null,
            'gateway_name'  => 'Asaas'
        ]);
    }
}
