<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string code
 * @property string phone_number
 * @property string expired_at
 *
 * @method static Builder valid() Extract only valid items.
 * @method static Builder invalid() Extract only invalid items.
 *
 * @mixin Builder
 */
class SmsConfirmation extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    # Scopes

    /**
     * Scope for static valid() method.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expired_at', '>=', now());
    }

    /**
     * Scope for static invalid() method.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInvalid(Builder $query): Builder
    {
        return $query->where('expired_at', '<', now());
    }
}
