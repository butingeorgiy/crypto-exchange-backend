<?php

namespace App\Services\ExchangeService;

use App\Models\ExchangeDirection;
use App\Models\ExchangeEntity;
use Illuminate\Support\Collection;
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
                    $this->getRepresentedEntity($item->firstEntity),
                    ['cost' => $relatedCosts[0]]
                ),
                'second_entity' => array_merge(
                    $this->getRepresentedEntity($item->secondEntity),
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
     *
     * @return array
     */
    #[ArrayShape([
        'id' => "int",
        'name' => "string",
        'alias' => "string",
        'limits' => "array",
        'icon' => "string",
        'type' => "string"
    ])]
    protected function getRepresentedEntity(ExchangeEntity $entity): array
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
            'type' => $entity->type
        ];
    }
}