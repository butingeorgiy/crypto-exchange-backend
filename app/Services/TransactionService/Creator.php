<?php

namespace App\Services\TransactionService;

use App\Models\ExchangeEntity;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService\Drivers\CompletableTransactionContract;
use Exception;

class Creator
{
    /**
     * Transaction model instance.
     *
     * @var Transaction
     */
    protected Transaction $transaction;

    /**
     * User model instance.
     *
     * @var User
     */
    protected User $user;

    /**
     * Transaction driver instance.
     *
     * @var CompletableTransactionContract|null
     */
    protected ?CompletableTransactionContract $transactionDriver;

    /**
     * Creator constructor.
     *
     * @param Transaction $transaction
     * @param User $user
     */
    public function __construct(Transaction $transaction, User $user)
    {
        $this->transaction = $transaction;
        $this->user = $user;
    }

    /**
     * Update transaction amount.
     *
     * IMPORTANT: Method will not check restrictions.
     *            You should delegate it to request.
     *
     * @param float|null $given
     * @param float|null $received
     *
     * @return void
     *
     * @throws Exception
     */
    public function updateAmount(float $given = null, float $received = null): void
    {
        if (!is_null($given) && !is_null($received)) {
            throw new Exception('Cannot specify both params.');
        }

        if ($given) {
            $receiveEntity = ExchangeEntity::query()
                ->select(['id', 'cost'])
                ->find($request->input('received_entity_id'));

            $receiveEntityAmount = $receiveEntity->calculateEquivalentOfAnotherEntity(
                $request->input('given_entity_id'),
                $request->input('given_entity_amount')
            );
        }
    }
}