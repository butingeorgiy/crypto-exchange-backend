<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int id
 * @property int user_id
 * @property string first_name
 * @property string last_name
 * @property string|null middle_name
 * @property string phone_number
 * @property string telegram_login
 * @property int status_id
 *
 * @mixin Builder
 */
class VerificationRequest extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    # Statuses IDs

    public static int $CREATED_STATUS_ID = 1;
    public static int $ACCEPTED_STATUS_ID = 2;
    public static int $DECLINED_STATUS_ID = 3;

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

    /**
     * Return request's status relation.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(VerificationStatus::class);
    }

    /**
     * Return request's attachments relations.
     *
     * @return MorphMany
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(FileAttachment::class, 'attachable');
    }
}
