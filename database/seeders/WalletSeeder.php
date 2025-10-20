<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Cliente SIMOP',
            'email' => 'cliente@simop.co.mz',
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'name' => 'ADRMM_MPESA',
            'provider' => 'mpesa',
            'callback_url' => 'https://cliente.adrmm.co.mz/callback/mpesa',
            'settings' => [
                'api_key' => 'XXXXXX',
                'public_key' => 'YYYYYY',
                'service_provider_code' => '171717',
                'env' => 'live',
            ],
            'active' => true,
        ]);

    }
}
