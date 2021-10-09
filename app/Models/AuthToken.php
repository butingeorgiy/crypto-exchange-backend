<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property int id
 * @property string token
 * @property int user_id
 * @property string expired_at
 *
 * @property User user
 *
 * @mixin Builder
 */
class AuthToken extends Model
{
    use HasFactory;

    public bool $timestamps = false;

    protected array $guarded = [];

    # Relations

    /**
     * Return auth token's user relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    # Scopes

    /**
     * Add validity condition to query.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expired_at', '>', DB::raw('NOW()'));
    }

    # Other methods

    /**
     * Determine if token is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return Carbon::now()->lt($this->expired_at);
    }
}
