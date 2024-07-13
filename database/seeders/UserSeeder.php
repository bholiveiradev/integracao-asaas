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
            'cpf_cnpj'      => fake()->cpf(), // format: 059.949.230-95 or 05994923095
            'phone'         => fake()->landlineNumber(), // format: (11) 9999-9999
            'mobile_phone'  => fake()->cellphoneNumber(), // format: (11) 99999-9999
            'email'         => $user->email,
        ]);

        User::factory(10)->create()
            ->each(function ($user) {
                $user->client()->create([
                    'cpf_cnpj'      => fake()->cpf(),
                    'phone'         => fake()->landlineNumber(),
                    'mobile_phone'  => fake()->cellphoneNumber(),
                    'email'         => $user->email,
                ]);
            });
    }
}
