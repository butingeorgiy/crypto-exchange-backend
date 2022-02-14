<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_statuses')->insert([
            [
                'id' => TransactionStatus::$PREPARED_STATUS_ID,
                'alias' => TransactionStatus::$PREPARED_STATUS_ALIAS,
                'description' => 'Транзакция только подготовлена и требует дополнительных действий со стороны пользователя.'
            ],
            [
                'id' => TransactionStatus::$PAYMENT_PENDING_STATUS_ID,
                'alias' => TransactionStatus::$PAYMENT_PENDING_STATUS_ALIAS,
                'description' => 'Ожидание оплаты со стороны пользователя.'
            ],
            [
                'id' => TransactionStatus::$PENDING_STATUS_ID,
                'alias' => TransactionStatus::$PENDING_STATUS_ALIAS,
                'description' => 'Заявка на транзакцию успешно сформирована и требует действий со стороны администрации сайта.'
            ],
            [
                'id' => TransactionStatus::$REJECTED_STATUS_ID,
                'alias' => TransactionStatus::$REJECTED_STATUS_ALIAS,
                'description' => 'Заявка на транзакцию отклонена.'
            ],
            [
                'id' => TransactionStatus::$COMPLETED_STATUS_ID,
                'alias' => TransactionStatus::$COMPLETED_STATUS_ALIAS,
                'description' => 'Транзакция успешно завершена с обеих сторон.'
            ]
        ]);
    }
}
