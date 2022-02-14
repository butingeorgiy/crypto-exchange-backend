<?php

namespace App\Services\TransactionService\Drivers;

use App\Models\Transaction;

interface CompletableTransactionContract
{
    /**
     * Execute actions.
     *
     * @param Transaction $transaction
     * @param array $options
     *
     * @return void
     */
    public function handle(Transaction $transaction, array $options = []): void;

    /**
     * Return data for client response.
     *
     * @param Transaction $transaction
     * @param array $options
     *
     * @return array
     */
    public function prepareDataForClient(Transaction $transaction, array $options = []): array;
}