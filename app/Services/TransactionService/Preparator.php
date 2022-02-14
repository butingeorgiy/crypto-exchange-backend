<?php

namespace App\Services\TransactionService;

use App\Models\ExchangeDirection;
use App\Models\ExchangeEntity;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use Illuminate\Support\Str;

class Preparator
{
    /**
     * Transaction UUID.
     *
     * @var string
     */
    protected string $id;

    /**
     * Given entity ID.
     *
     * @var int
     */
    protected int $givenEntityId;

    /**
     * Given entity amount.
     *
     * @var float
     */
    protected float $givenEntityAmount;

    /**
     * Received entity ID.
     *
     * @var int
     */
    protected int $receivedEntityId;

    /**
     * Received entity amount.
     *
     * @var float
     */
    protected float $receivedEntityAmount;

    /**
     * Is transaction inverted.
     *
     * @var bool
     */
    protected bool $inverted;

    /**
     * Preparator constructor.
     *
     * @param int $givenEntityId
     * @param float $givenEntityAmount
     * @param int $receivedEntityId
     * @param float $receivedEntityAmount
     * @param bool $inverted
     */
    public function __construct(int $givenEntityId, float $givenEntityAmount,
                                int $receivedEntityId, float $receivedEntityAmount, bool $inverted)
    {
        $this->givenEntityId = $givenEntityId;
        $this->givenEntityAmount = $givenEntityAmount;

        $this->receivedEntityId = $receivedEntityId;
        $this->receivedEntityAmount = $receivedEntityAmount;

        $this->inverted = $inverted;

        $this->generateUniqueUuid();
    }

    /**
     * Generate unique UUID.
     *
     * @return void
     */
    protected function generateUniqueUuid(): void
    {
        while (true) {
            if (Transaction::where('id', $uuid = Str::uuid())->exists()) {
                continue;
            }

            $this->id = $uuid;

            break;
        }
    }

    /**
     * Get given entity model instance.
     *
     * @return ExchangeEntity
     */
    protected function getGivenEntityModel(): ExchangeEntity
    {
        return ExchangeEntity::select([
            'id', 'name', 'type', 'cost'
        ])->findOrFail($this->givenEntityId);
    }

    /**
     * Get received entity model.
     *
     * @return ExchangeEntity
     */
    protected function getReceivedEntityModel(): ExchangeEntity
    {
        return ExchangeEntity::select([
            'id', 'name', 'type', 'cost'
        ])->findOrFail($this->receivedEntityId);
    }

    /**
     * Get transaction type.
     *
     * @param string $givenEntityType
     * @param string $receivedEntityType
     *
     * @return string
     */
    protected function getTransactionType(string $givenEntityType,
                                          string $receivedEntityType): string
    {
        return $givenEntityType . '_to_' . $receivedEntityType;
    }

    /**
     * Save transaction.
     *
     * @return Transaction
     */
    public function save(): Transaction
    {
        $givenEntity = $this->getGivenEntityModel();
        $receivedEntity = $this->getReceivedEntityModel();

        return Transaction::query()->create([
            'id' => $this->getTransactionUuid(),
            'given_entity_id' => $givenEntity->id,
            'given_entity_name' => $givenEntity->name,
            'given_entity_amount' => $this->givenEntityAmount,
            'given_entity_cost' => $givenEntity->cost,
            'received_entity_id' => $receivedEntity->id,
            'received_entity_name' => $receivedEntity->name,
            'received_entity_amount' => $this->receivedEntityAmount,
            'received_entity_cost' => $receivedEntity->cost,
            'direction_id' => ExchangeDirection::getIdByEntities($this->givenEntityId, $this->receivedEntityId),
            'inverted' => $this->inverted,
            'type' => $this->getTransactionType($givenEntity->type, $receivedEntity->type),
            'status_id' => TransactionStatus::$PREPARED_STATUS_ID
        ]);
    }

    /**
     * Get next step url.
     *
     * @return string
     */
    public function getNextStepUrl(): string
    {
        return config('app.url') . '/v1/transactions/create?uuid=' . $this->id;
    }

    /**
     * Get transaction UUID.
     *
     * @return string
     */
    public function getTransactionUuid(): string
    {
        return $this->id;
    }
}
