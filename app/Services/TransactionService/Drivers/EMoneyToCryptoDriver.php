<?php

namespace App\Services\TransactionService\Drivers;

use App\Models\Transaction;

class EMoneyToCryptoDriver implements CompletableTransactionContract
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
    public function prepareDataForClient(Transaction $transaction, array $options = []): array
    {
        return [];
    }
}