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
 *
 * @property User user
 *
 * @mixin Builder
 */
class EmailVerificationRequest extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $keyType = 'string';

    # Relations

    /**
     * Return email verification request's user relatioin.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    # Other method

    /**
     * Prepare unique model instance.
     *
     * @return EmailVerificationRequest
     */
    public static function prepareUnique(): EmailVerificationRequest
    {
        while (true) {
            $uuid = Str::uuid();

            if (!EmailVerificationRequest::where('id', $uuid)->exists()) break;
        }

        return new EmailVerificationRequest([
            'id' => $uuid,
            'salt' => Str::random()
        ]);
    }
}
