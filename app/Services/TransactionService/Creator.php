<?php

namespace App\Services\TransactionService;

use App\Models\ExchangeEntity;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\User;
use App\Services\TransactionService\Drivers\CompletableTransactionContract;
use App\Services\TransactionService\Drivers\CryptoToCryptoDriver;
use App\Services\TransactionService\Drivers\CryptoToEMoneyDriver;
use App\Services\TransactionService\Drivers\EMoneyToCryptoDriver;
use Exception;
use Illuminate\Support\Facades\DB;

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
     * Transaction status after creating.
     *
     * @var int
     */
    protected int $nextStatusId;

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

        $this->resolveDriverInstance();
        $this->resolveNextStatus();
    }

    /**
     * Create transaction.
     *
     * @param array $options
     *
     * @return array
     */
    public function create(array $options = []): array
    {
        return DB::transaction(function () use ($options) {
            $this->transaction->update([
                'user_id' => $this->user->id,
                'user_full_name' => $this->user->getFullName(),
                'user_phone_number' => $this->user->phone_number,
                'user_email' => $this->user->email,
                'status_id' => $this->nextStatusId,
                'options' => $options
            ]);

            if (is_null($this->transactionDriver)) {
                return [];
            }

            $this->transactionDriver->handle($this->transaction, $options);

            return $this->transactionDriver->prepareDataForClient($this->transaction, $options);
        });
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
            /** @var ExchangeEntity $receiveEntity */
            $receiveEntity = ExchangeEntity::query()
                ->select(['id', 'cost'])
                ->find($this->transaction->received_entity_id);

            $received = $receiveEntity->calculateEquivalentOfAnotherEntity(
                $this->transaction->given_entity_id,
                $given
            );
        } else {
            /** @var ExchangeEntity $givenEntity */
            $givenEntity = ExchangeEntity::query()
                ->select(['id', 'cost'])
                ->find($this->transaction->given_entity_id);

            $given = $givenEntity->calculateEquivalentOfAnotherEntity(
                $this->transaction->received_entity_id,
                $received
            );
        }

        $this->transaction->update([
            'given_entity_amount' => $given,
            'received_entity_amount' => $received
        ]);
    }

    /**
     * Get message for client.
     *
     * @return string
     */
    public function getMessageForClient(): string
    {
        if (is_null($this->transactionDriver)) {
            return 'Заявка успешно сформирована! Ожидайте, менеджер с Вами свяжется в ближайшее время.';
        }

        return $this->transactionDriver->prepareMessageForClient();
    }

    /**
     * Resolve driver instance.
     *
     * @return void
     */
    protected function resolveDriverInstance(): void
    {
        $this->transactionDriver = match ($this->transaction->type) {
            'e_money_to_crypto' => new EMoneyToCryptoDriver,
            'crypto_to_e_money' => new CryptoToEMoneyDriver,
            'crypto_to_crypto' => new CryptoToCryptoDriver,
            default => null
        };
    }

    /**
     * Resolve next status ID.
     *
     * @return void
     */
    protected function resolveNextStatus(): void
    {
        $this->nextStatusId = match ($this->transaction->type) {
            'crypto_to_e_money',
            'crypto_to_crypto' => TransactionStatus::$PAYMENT_PENDING_STATUS_ID,
            default => TransactionStatus::$PENDING_STATUS_ID
        };
    }
}
