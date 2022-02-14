<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'id' => 1,
            'is_verified' => true
        ]);

        User::factory()->create([
            'id' => 2,
            'is_verified' => false
        ]);
    }
}
