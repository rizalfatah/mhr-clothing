<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MomentPackage extends Model
{
    protected $table = 'moment_packages';

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'is_active' => 'boolean', 'sort_order' => 'integer'];
}
