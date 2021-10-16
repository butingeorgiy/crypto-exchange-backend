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
class Role extends Model
{
    # Role IDs

    static int $REGULAR_ROLE_ID = 1;
    static int $ADMIN_ROLE_ID = 2;
}
