<?php

namespace App\Services\TransactionService\Drivers;

use App\Models\ExchangeEntity;
use App\Models\Transaction;
use JetBrains\PhpStorm\ArrayShape;

class CryptoToCryptoDriver implements CompletableTransactionContract
{
    /**
     * @inheritDoc
     */
    public function handle(Transaction $transaction, array $options = []): void
    {
        // do nothing
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'wallet_address' => "string",
        'transfer_amount' => "float",
        'next_step_url' => "string"
    ])]
    public function prepareDataForClient(Transaction $transaction, array $options = []): array
    {
        if ($given = ExchangeEntity::select(['id', 'alias'])->find($transaction->given_entity_id)) {
            $transferAmount = $transaction->given_entity_amount . ' ' . $given->alias;
        } else {
            $transferAmount = $transaction->given_entity_amount . ' у.е.';
        }

        return [
            'wallet_address' => '7a6ffed9-4252-427e-af7d-3dcaaf2db2df',
            'transfer_amount' => $transferAmount,
            'next_step_url' => config('app.url') . '/v1/transactions/complete?uuid=' . $transaction->id
        ];
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function prepareMessageForClient(): string
    {
        return 'Заявка успешно сформирована! Вам необходимо перевести указанную сумму на наш крипто-кошелек, далее с Вами свяжется наш менеджер.';
    }
}
