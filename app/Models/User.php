<?php

namespace App\Models;

use App\Services\AuthenticationService\Traits\HasUniqueHashing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int id
 * @property string first_name
 * @property string|null last_name
 * @property string|null middle_name
 * @property string phone_number
 * @property string email
 * @property bool is_email_verified
 * @property string password
 * @property string ref_code
 * @property bool is_verified
 * @property string created_at
 * @property string|null deleted_at
 *
 * @property Collection<AuthToken> tokens
 * @property Collection<Role> roles
 * @property Collection<EmailVerificationRequest> emailVerifications
 *
 * @method static Builder byPhone(string $phone) Retrieve users by phone number.
 * @method static Builder byRef(string $refCode) Retrieve users by ref code.
 *
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use HasUniqueHashing;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'is_email_verified' => 'boolean',
        'is_verified' => 'boolean'
    ];

    # Relations

    /**
     * Return user's roles relations.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_has_role');
    }

    /**
     * Return user's email verification requests relations.
     *
     * @return HasMany
     */
    public function emailVerifications(): HasMany
    {
        return $this->hasMany(EmailVerificationRequest::class);
    }

    /**
     * Return user's verification requests relations.
     *
     * @return HasMany
     */
    public function verificationRequests(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    # Scopes

    /**
     * Scope for byPhone() method.
     *
     * @param Builder $query
     * @param string $phone
     *
     * @return Builder
     */
    public function scopeByPhone(Builder $query, string $phone): Builder
    {
        return $query->where('phone_number', $phone);
    }

    /**
     * Scope for byRef() method.
     *
     * @param Builder $query
     * @param string $refCode
     *
     * @return Builder
     */
    public function scopeByRef(Builder $query, string $refCode): Builder
    {
        return $query->where('ref_code', $refCode);
    }

    # Other methods

    /**
     * Is user verified.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Generate unique referral code for user.
     *
     * @return string
     */
    public static function generateRefCode(): string
    {
        while (true) {
            $refCode = Str::random(8);

            if (!User::byRef($refCode)->exists()) break;
        }

        return $refCode;
    }

    /**
     * Get user's full name.
     *
     * @return string
     */
    public function getFullName(): string
    {
        return trim("$this->last_name $this->first_name $this->middle_name");
    }
}
