<?php

namespace App\Models;

use App\Services\AuthenticationService\Traits\HasAuthToken;
use App\Services\AuthenticationService\Traits\HasUniqueHashing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string first_name
 * @property string|null last_name
 * @property string|null middle_name
 * @property string phone_number
 * @property string email
 * @property string password
 * @property string ref_code
 * @property bool is_verified
 * @property string created_at
 * @property string|null deleted_at
 *
 * @property Collection<AuthToken> tokens
 * @property Collection<Role> roles
 *
 * @method static Builder byPhone(string $phone) Retrieve users by phone number.
 *
 * @mixin Builder
 */
class User extends Model
{
    use HasFactory;
    use HasAuthToken;
    use HasUniqueHashing;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    # Relations

    /**
     * Return user's tokens relations.
     *
     * @return HasMany
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(AuthToken::class);
    }

    /**
     * Return user's roles relations.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_has_role');
    }

    # Scopes

    /**
     * Scope for byPhone() method.
     *
     * @param Builder $query
     * @param string $phone
     * @return Builder
     */
    public function scopeByPhone(Builder $query, string $phone): Builder
    {
        return $query->where('phone_number', $phone);
    }
}
