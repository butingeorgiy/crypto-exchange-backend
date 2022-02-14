<?php

namespace App\Services\TransactionService;

use App\Exceptions\ModelExceptions\ExchangeDirection\NotFoundException as ExchangeDirectionNotFoundException;
use App\Exceptions\ModelExceptions\ExchangeEntity\NotFoundException as ExchangeEntityNotFoundException;
use App\Exceptions\ModelExceptions\User\NotFoundException as UserNotFoundException;
use App\Models\ExchangeDirection;
use App\Models\ExchangeEntity;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class ComplexValidator
{
    /**
     * Determine does direction exist.
     *
     * IMPORTANT: This method do not consider enabling of directions.
     *            So even for disabled direction this method can return True.
     *
     * @param int $givenEntityId
     * @param int $receivedEntityId
     *
     * @return bool
     */
    public function hasDirection(int $givenEntityId, int $receivedEntityId): bool
    {
        return ExchangeDirection::query()
            ->select(['first_entity_id', 'second_entity_id', 'inverting_allowed'])
            ->where(function (Builder $builder) use ($givenEntityId, $receivedEntityId) {
                $builder
                    ->where([
                        ['first_entity_id', $givenEntityId],
                        ['second_entity_id', $receivedEntityId]
                    ])
                    ->orWhere([
                        ['first_entity_id', $receivedEntityId],
                        ['second_entity_id', $givenEntityId],
                        ['inverting_allowed', true]
                    ]);
            })
            ->exists();
    }

    /**
     * Determine can user prepare transfer.
     *
     * @param int $directionId
     * @param bool $inverted
     * @param int|null $givenEntityAmount
     * @param int|null $receivedEntityAmount
     * @param int|null $userId
     *
     * @return bool
     *
     * @throws Exception
     * @throws ExchangeDirectionNotFoundException
     * @throws ExchangeEntityNotFoundException
     * @throws UserNotFoundException
     */
    public function canUserPrepareTransaction(int  $directionId, bool $inverted, ?int $givenEntityAmount = null,
                                              ?int $receivedEntityAmount = null, ?int $userId = null): bool
    {
        $direction = ExchangeDirection::select(['id', 'first_entity_id', 'second_entity_id'])->find($directionId)
            ?: throw new ExchangeDirectionNotFoundException;

        # Resolving given and received entities.

        /**
         * @var ExchangeEntity $givenEntity
         * @var ExchangeEntity $receivedEntity
         */

        if ($inverted) {
            $givenEntity = $direction
                ->secondEntity()
                ->select(['id', 'cost', 'min_limit', 'max_limit', 'no_verify_limit', 'no_auth_limit'])
                ->firstOrFail();

            $receivedEntity = $direction
                ->firstEntity()
                ->select(['id', 'cost', 'min_limit', 'max_limit', 'no_verify_limit', 'no_auth_limit'])
                ->firstOrFail();
        } else {
            $givenEntity = $direction
                ->firstEntity()
                ->select(['id', 'cost', 'min_limit', 'max_limit', 'no_verify_limit', 'no_auth_limit'])
                ->firstOrFail();

            $receivedEntity = $direction
                ->secondEntity()
                ->select(['id', 'cost', 'min_limit', 'max_limit', 'no_verify_limit', 'no_auth_limit'])
                ->firstOrFail();
        }

        # Calculate pair entity equivalent amount.

        if (is_null($receivedEntityAmount)) {
            $receivedEntityAmount = $receivedEntity->calculateEquivalentOfAnotherEntity(
                $givenEntity->id, $givenEntityAmount
            );
        } else if (is_null($givenEntityAmount)) {
            $givenEntityAmount = $givenEntity->calculateEquivalentOfAnotherEntity(
                $receivedEntity->id, $receivedEntityAmount
            );
        } else {
            throw new Exception('Must specify $givenEntityAmount or $receivedEntityAmount.');
        }

        # Check minimal amount.

        if ($givenEntity->min_limit > $givenEntityAmount ||
            $receivedEntity->min_limit > $receivedEntityAmount) {
            return false;
        }

        if (is_null($userId)) {
            return $givenEntity->no_auth_limit >= $givenEntityAmount &&
                $receivedEntity->no_auth_limit >= $receivedEntityAmount;
        } else {
            if (!$user = User::select('id', 'is_verified')->find($userId)) {
                throw new UserNotFoundException;
            }

            if ($user->isVerified()) {
                # If user is verified, check max limit.

                if ($givenEntity->max_limit < $givenEntityAmount ||
                    $receivedEntity->max_limit < $receivedEntityAmount) {
                    return false;
                }
            } else {
                # If user is not verified, check no-verified limit.

                if ($givenEntity->no_verify_limit < $givenEntityAmount ||
                    $receivedEntity->no_verify_limit < $receivedEntityAmount) {
                    return false;
                }
            }
        }

        return true;
    }

    public function checkExtraOptions(string $type, array $options = []): void
    {
        // TODO: write method
    }
}
