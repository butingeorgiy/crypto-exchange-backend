<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int|null user_id
 * @property string|null user_full_name
 * @property string|null user_phone_number
 * @property string|null user_email
 * @property int direction_id
 * @property boolean inverted
 * @property string given_entity_title
 * @property double given_entity_amount
 * @property double given_entity_cost
 * @property string received_entity_title
 * @property double received_entity_amount
 * @property double received_entity_cost
 * @property string type
 * @property int status_id
 * @property string created_at
 * @property string updated_at
 *
 * @property User|null user
 * @property TransactionStatus status
 *
 * @mixin Builder
 */
class Transaction extends Model
{
    /**
     * @inheritdoc
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @inheritdoc
     *
     * @var array
     */
    protected $guarded = [];

    # Relations

    /**
     * Return user relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return status relation.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class);
    }

    # Other methods

    /**
     * Get meta direction ID.
     *
     * @param array $fields
     * @return Builder|Model|null
     */
    public function getMetaDirection(array $fields = ['*']): Builder|Model|null
    {
        /** @var ExchangeDirection $direction */
        return ExchangeDirection::query()
            ->select($fields)
            ->where('enabled', true)
            ->first($this->meta_direction_id);
    }

    /**
     * Get meta inverted.
     *
     * @return bool
     */
    public function getMetaInverted(): bool
    {
        return $this->meta_inverted;
    }
}
