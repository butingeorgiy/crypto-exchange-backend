<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int exchange_entity_id
 * @property string card_color_type
 * @property bool visible
 *
 * @property ExchangeEntity entity
 *
 * @method static Builder visible() Add visibility condition to query.
 *
 * @mixin Builder
 */
class RateDashboardEntity extends Model
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
        'visible' => 'boolean'
    ];

    # Relations

    /**
     * Return exchange entity relation.
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(ExchangeEntity::class, 'exchange_entity_id');
    }

    # Scopes

    /**
     * Add visibility condition to query.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('visible', true);
    }
}
