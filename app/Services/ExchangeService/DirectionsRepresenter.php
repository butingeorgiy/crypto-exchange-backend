<?php

namespace App\Services\ExchangeService;

use App\Models\ExchangeDirection;
use App\Models\ExchangeEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DirectionsRepresenter
{
    /**
     * Directions list.
     *
     * @var Collection<ExchangeDirection>
     */
    protected Collection $directions;

    /**
     * EntitiesRepresenter constructor.
     *
     * @param array<ExchangeDirection>|Collection<ExchangeDirection> $items
     */
    public function __construct(array|Collection $items)
    {
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        $this->directions = $items;
    }

    /**
     * Return represented directions.
     *
     * @return array
     */
    public function getRepresented(): array
    {
        return $this->directions->map(function (ExchangeDirection $item) {
            $relatedCosts = $item->calculateRelatedCosts();

            return [
                'first_entity' => array_merge(
                    $this->getRepresentedEntity($item->firstEntity, true),
                    ['cost' => $relatedCosts[0]]
                ),
                'second_entity' => array_merge(
                    $this->getRepresentedEntity($item->secondEntity, false),
                    ['cost' => $relatedCosts[1]]
                ),
                'inverting_allowed' => $item->inverting_allowed
            ];
        })->toArray();
    }

    /**
     * Get represented entity.
     *
     * @param ExchangeEntity $entity
     * @param bool $given
     *
     * @return array
     */
    #[ArrayShape([
        'id' => "int",
        'name' => "string",
        'alias' => "string",
        'limits' => "array",
        'icon' => "string",
        'type' => "string",
        'pairs' => "array"
    ])]
    protected function getRepresentedEntity(ExchangeEntity $entity, bool $given): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'alias' => $entity->alias,
            'limits' => [
                'min' => $entity->min_limit,
                'max' => $entity->max_limit,
                'max_not_authenticated' => $entity->no_auth_limit,
                'max_not_verified' => $entity->no_verify_limit
            ],
            'icon' => $entity->getLinkOnIcon(),
            'type' => $entity->type,
            'pairs' => $this->getRepresentedPairs($entity->id, $given)
        ];
    }

    /**
     * Retrieve pairs models.
     *
     * @param int $entityId
     * @param bool $given
     *
     * @return Collection
     */
    protected function retrievePairsModels(int $entityId, bool $given): Collection
    {
        $directions = DB::table('exchange_directions')
            ->select(['first_entity_id', 'second_entity_id'])
            ->where(function (Builder $query) use ($entityId, $given) {
                if ($given) {
                    $query->where('first_entity_id', $entityId);
                } else {
                    $query->where('second_entity_id', $entityId);
                }
            })
            ->get();

        $entityIds = $directions->map(function ($item) use ($given) {
            return $given ? $item->second_entity_id : $item->first_entity_id;
        });

        return ExchangeEntity::query()
            ->select([
                'id', 'name', 'alias', 'icon',
                'cost', 'min_limit', 'max_limit',
                'no_auth_limit', 'no_verify_limit', 'type'
            ])
            ->find($entityIds);
    }

    /**
     * Get represented pairs.
     *
     * @param int $entityId
     * @param bool $given
     *
     * @return array
     */
    protected function getRepresentedPairs(int $entityId, bool $given): array
    {
        $pairs = $this->retrievePairsModels($entityId, $given);

        return $pairs->map(function (ExchangeEntity $entity) {
            return [
                'id' => $entity->id,
                'name' => $entity->name,
                'alias' => $entity->alias,
                'limits' => [
                    'min' => $entity->min_limit,
                    'max' => $entity->max_limit,
                    'max_not_authenticated' => $entity->no_auth_limit,
                    'max_not_verified' => $entity->no_verify_limit
                ],
                'icon' => $entity->getLinkOnIcon(),
                'type' => $entity->type
            ];
        })->toArray();
    }
}
