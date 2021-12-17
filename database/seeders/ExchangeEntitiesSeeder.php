<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('exchange_entities')->insert([
            [
                'name' => 'Kaspi Bank',
                'alias' => 'KZT',
                'icon' => 'dTrz3JfdEpjeMQgF.png',
                'cost' => 1,
                'min_limit' => 20000,
                'max_limit' => 1000000,
                'no_auth_limit' => 50000,
                'no_verify_limit' => 150000,
                'type' => 'e_money'
            ],
            [
                'name' => 'Tether',
                'alias' => 'THZ',
                'icon' => 'ZZx6dyGXqulPUW4z.png',
                'cost' => 437.18,
                'min_limit' => 45,
                'max_limit' => 2270,
                'no_auth_limit' => 112,
                'no_verify_limit' => 340,
                'type' => 'crypto'
            ],
            [
                'name' => 'Bitcoin',
                'alias' => 'BTC',
                'icon' => '8P8tVZWrEKdfHW4L.png',
                'cost' => 20592816.37,
                'min_limit' => 0.001,
                'max_limit' => 0.0485,
                'no_auth_limit' => 0.0024,
                'no_verify_limit' => 0.0072,
                'type' => 'crypto'
            ],
            [
                'name' => 'Наличные',
                'alias' => 'KZT',
                'icon' => 'H8i5dFL2T5r18oiA.png',
                'cost' => 1,
                'min_limit' => 20000,
                'max_limit' => 1000000,
                'no_auth_limit' => 50000,
                'no_verify_limit' => 150000,
                'type' => 'cash'
            ]
        ]);
    }
}
