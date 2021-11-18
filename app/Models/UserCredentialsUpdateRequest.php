<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string id
 * @property string salt
 * @property int user_id
 * @property string|null email
 * @property string|null hashed_password
 *
 * @property User user
 *
 * @mixin Builder
 */
class UserCredentialsUpdateRequest extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    # Relations

    /**
     * Return request's user relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    # Other methods

    /**
     * Prepare unique model instance.
     *
     * @return UserCredentialsUpdateRequest
     */
    public static function prepareUnique(): UserCredentialsUpdateRequest
    {
        while (true) {
            $uuid = Str::uuid();

            if (!UserCredentialsUpdateRequest::where('id', $uuid)->exists()) break;
        }

        return new UserCredentialsUpdateRequest([
            'id' => $uuid,
            'salt' => Str::random()
        ]);
    }
}
