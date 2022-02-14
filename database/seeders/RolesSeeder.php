<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'alias' => 'regular-user',
                'description' => 'Regular user'
            ],
            [
                'id' => 2,
                'alias' => 'admin',
                'description' => 'Admin user'
            ]
        ]);

        DB::table('user_has_role')->insert([
            [
                'user_id' => 1,
                'role_id' => Role::$REGULAR_ROLE_ID
            ],
            [
                'user_id' => 2,
                'role_id' => Role::$REGULAR_ROLE_ID
            ]
        ]);
    }
}
