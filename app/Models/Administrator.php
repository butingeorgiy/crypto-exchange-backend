<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use JetBrains\PhpStorm\Pure;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property bool email_verified_at
 * @property string password
 * @property string|null remember_token
 * @property string|null abilities
 * @property string|null created_at
 * @property string|null updated_at
 *
 * @mixin Builder
 */
class Administrator extends Authenticatable implements FilamentUser
{
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
        'email_verified_at' => 'boolean'
    ];

    # Other methods

    #[Pure]
    public function canAccessFilament(): bool
    {
        return $this->isVerified();
    }

    /**
     * Determine did administrator verify his E-mail address.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at === true;
    }
}
