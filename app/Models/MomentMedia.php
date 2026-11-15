<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MomentMedia extends Model
{
    protected $table = 'moment_media';

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'is_active' => 'boolean', 'sort_order' => 'integer'];
}
