<?php

namespace Database\Seeders;

use App\Models\VerificationRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VerificationStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('verification_statuses')->insert([
            [
                'id' => VerificationRequest::$CREATED_STATUS_ID,
                'name' => 'Создан'
            ],
            [
                'id' => VerificationRequest::$ACCEPTED_STATUS_ID,
                'name' => 'Одобрен'
            ],
            [
                'id' => VerificationRequest::$DECLINED_STATUS_ID,
                'name' => 'Отклонен'
            ]
        ]);
    }
}
