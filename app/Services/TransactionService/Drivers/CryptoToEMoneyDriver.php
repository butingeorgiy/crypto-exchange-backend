<?php

namespace App\Services\TransactionService\Drivers;

use App\Models\Transaction;
use JetBrains\PhpStorm\ArrayShape;

class CryptoToEMoneyDriver implements CompletableTransactionContract
{

    /**
     * @inheritDoc
     */
    public function handle(Transaction $transaction, array $options = []): void
    {
        //
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'wallet_address' => "string",
        'transfer_amount' => "float"
    ])]
    public function prepareDataForClient(Transaction $transaction, array $options = []): array
    {
        return [
            'wallet_address' => '7a6ffed9-4252-427e-af7d-3dcaaf2db2df',
            'transfer_amount' => $transaction->given_entity_amount
        ];
    }
}