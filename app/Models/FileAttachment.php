<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int id
 * @property string title
 * @property string file_path
 * @property int attachable_id
 * @property string attachable_type
 * @property string created_at
 * @property string|null deleted_at
 *
 * @mixin Builder
 */
class FileAttachment extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    # Relations

    /**
     * @return MorphTo
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
