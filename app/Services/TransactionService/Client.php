<?php

namespace App\Services\TransactionService;

use App\Models\Transaction;
use App\Models\User;
use JetBrains\PhpStorm\Pure;

class Client
{
    /**
     * Return transaction validator instance.
     *
     * @return ComplexValidator
     */
    #[Pure]
    public function validator(): ComplexValidator
    {
        return new ComplexValidator;
    }

    /**
     * Return transaction preparator instance.
     *
     * @param int $givenEntityId
     * @param float $givenEntityAmount
     * @param int $receivedEntityId
     * @param float $receivedEntityAmount
     * @param bool $inverted
     *
     * @return Preparator
     */
    public function preparationService(int $givenEntityId, float $givenEntityAmount,
                                       int $receivedEntityId, float $receivedEntityAmount, bool $inverted): Preparator
    {
        return new Preparator(
            $givenEntityId,
            $givenEntityAmount,
            $receivedEntityId,
            $receivedEntityAmount,
            $inverted
        );
    }

    /**
     * Return transaction creator instance.
     *
     * @param Transaction $transaction
     * @param User $user
     *
     * @return Creator
     */
    public function creationService(Transaction $transaction, User $user): Creator
    {
        return new Creator($transaction, $user);
    }
}
