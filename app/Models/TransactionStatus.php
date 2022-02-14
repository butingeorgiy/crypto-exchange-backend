<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string alias
 * @property string description
 *
 * @mixin Builder
 */
class TransactionStatus extends Model
{
    # Status IDs

    static int $PREPARED_STATUS_ID = 1;
    static int $PAYMENT_PENDING_STATUS_ID = 2;
    static int $PENDING_STATUS_ID = 3;
    static int $REJECTED_STATUS_ID = 4;
    static int $COMPLETED_STATUS_ID =5;

    # Status Aliases

    static string $PREPARED_STATUS_ALIAS = 'prepared';
    static string $PAYMENT_PENDING_STATUS_ALIAS = 'payment_pending';
    static string $PENDING_STATUS_ALIAS = 'pending';
    static string $REJECTED_STATUS_ALIAS = 'rejected';
    static string $COMPLETED_STATUS_ALIAS = 'completed';
}
