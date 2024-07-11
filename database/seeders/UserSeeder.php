<?php

namespace Database\Seeders;

use App\Models\{Client, User};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user->client()->create([
            'cpf_cnpj' => '99999999999',
            'phone' => '1199999999',
            'mobile_phone' => '11999999999',
            'email' => 'test@example.com',
        ]);
    }
}
