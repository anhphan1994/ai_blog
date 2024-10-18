<?php

namespace Database\Seeders;

use App\Models\PlatformAccount;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('platform_accounts')->insert([
            'uuid' => fake()->uuid(),
            'platform_name' => PlatformAccount::PLATFORM_WORDPRESS,
            'username' => 'admin',
            'api_key' => 'wiRb 8Ab2 d3ZF 2PmS DXVH 5UiO',
            'url' => 'https://routinely-rapid-moray.ngrok-free.app',
        ]);

    }
}
