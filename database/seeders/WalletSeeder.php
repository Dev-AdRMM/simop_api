<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Cliente SIMOP',
            'email' => 'cliente@simop.co.mz',
        ]);

        Wallet::create([
            'id' => Str::uuid(),
            'user_id' => 1,
            'name' => 'ADRMM_MPESA',
            'provider' => 'mpesa',
            'callback_url' => 'https://cliente.adrmm.co.mz/callback/mpesa',
            'settings' => json_encode([
                'api_key' => 'XXXXXX',
                'public_key' => 'YYYYYY',
                'service_provider_code' => '171717',
                'env' => 'live'
            ]),
            'balance' => 0,
            'status' => 'active',
            'active' => true,
            'suspension_reason' => null,
            'api_key' => Str::uuid(),
        ]);
    }
}





