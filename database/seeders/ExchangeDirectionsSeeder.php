<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeDirectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('exchange_directions')->insert([
            [
                'first_entity_id' => 1,
                'second_entity_id' => 2,
                'fee_coefficient' => 0.014,
                'inverted_fee_coefficient' => 0.018,
                'inverting_allowed' => true
            ],
            [
                'first_entity_id' => 1,
                'second_entity_id' => 3,
                'fee_coefficient' => 0.014,
                'inverted_fee_coefficient' => 0.014,
                'inverting_allowed' => true
            ],
            [
                'first_entity_id' => 3,
                'second_entity_id' => 4,
                'fee_coefficient' => 0.014,
                'inverted_fee_coefficient' => 0.0,
                'inverting_allowed' => false
            ],
            [
                'first_entity_id' => 3,
                'second_entity_id' => 2,
                'fee_coefficient' => 0.014,
                'inverted_fee_coefficient' => 0.0,
                'inverting_allowed' => false
            ],
            [
                'first_entity_id' => 2,
                'second_entity_id' => 4,
                'fee_coefficient' => 0.01,
                'inverted_fee_coefficient' => 0.01,
                'inverting_allowed' => true
            ]
        ]);
    }
}
