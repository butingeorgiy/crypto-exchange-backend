<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int first_entity_id
 * @property int second_entity_id
 * @property double fee_coefficient
 * @property double inverted_fee_coefficient
 * @property boolean inverting_allowed
 * @property boolean enabled
 *
 * @property ExchangeEntity firstEntity
 * @property ExchangeEntity secondEntity
 *
 * @method static Builder enabled() Add enabling condition to query.
 *
 * @mixin Builder
 */
class ExchangeDirection extends Model
{
    /**
     * @inheritdoc
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @inheritdoc
     *
     * @var string[]
     */
    protected $casts = [
        'inverting_allowed' => 'boolean',
        'enabled' => 'boolean'
    ];


    # Relations

    /**
     * Return direction's first entity relation.
     *
     * @return BelongsTo
     */
    public function firstEntity(): BelongsTo
    {
        return $this->belongsTo(ExchangeEntity::class);
    }

    /**
     * Return direction's second entity relation.
     *
     * @return BelongsTo
     */
    public function secondEntity(): BelongsTo
    {
        return $this->belongsTo(ExchangeEntity::class);
    }


    # Scopes

    /**
     * Add enabling condition to query.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }


    # Other methods

    /**
     * Calculated related costs of direction's entities.
     *
     * 0 index – cost of first entity, 1 index – cost of second entity.
     *
     * @return float[]
     */
    public function calculateRelatedCosts(): array
    {
        if ($this->firstEntity->cost > $this->secondEntity->cost) {
            return [
                round($this->firstEntity->cost / $this->secondEntity->cost, 2),
                1
            ];
        } else if ($this->firstEntity->cost < $this->secondEntity->cost) {
            return [
                1,
                round($this->secondEntity->cost / $this->firstEntity->cost, 2)
            ];
        }

        return [1, 1];
    }

    /**
     * Get all enabled directions.
     *
     * @return Collection|array
     */
    public static function getAllEnabled(): Collection|array
    {
        return ExchangeDirection::with(['firstEntity', 'secondEntity'])
            ->whereHas('firstEntity', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->whereHas('secondEntity', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('enabled', true)->get();
    }
}
