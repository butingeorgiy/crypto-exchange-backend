<?php

namespace App\Services\TransactionService\Drivers;

use App\Models\Transaction;

interface CompletableTransactionContract
{
    /**
     * Execute actions.
     *
     * @param Transaction $transaction
     *
     * @return void
     */
    public function handle(Transaction $transaction): void;

    /**
     * Return data for client response.
     *
     * @param Transaction $transaction
     *
     * @return array
     */
    public function clientData(Transaction $transaction): array;
}