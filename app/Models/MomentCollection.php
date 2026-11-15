<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MomentCollection extends Model
{
    protected $table = 'moment_collections';

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'is_active' => 'boolean', 'sort_order' => 'integer'];
}
