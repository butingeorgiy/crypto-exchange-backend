<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateDashboardEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rate_dashboard_entities')->insert([
            [
                'exchange_entity_id' => 2,
                'card_color_type' => 'green'
            ],
            [
                'exchange_entity_id' => 3,
                'card_color_type' => 'yellow'
            ]
        ]);
    }
}
