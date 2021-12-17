<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string alias
 * @property string icon
 * @property double cost
 * @property double|null min_limit
 * @property double|null max_limit
 * @property double|null no_auth_limit
 * @property double|null no_verify_limit
 * @property string type available values: `cash`, `e_money`, `crypto`
 * @property boolean enabled
 *
 * @method static Builder enabled() Add enabling condition to query.
 *
 * @mixin Builder
 */
class ExchangeEntity extends Model
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
        'enabled' => 'boolean'
    ];


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
     * Get link on icon.
     *
     * @return string
     */
    public function getLinkOnIcon(): string
    {
        return asset('storage/entity_icons/' . $this->icon);
    }
}
