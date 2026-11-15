<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MomentSetting extends Model
{
    protected $table = 'moment_settings';

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'is_active' => 'boolean', 'sort_order' => 'integer'];
}
