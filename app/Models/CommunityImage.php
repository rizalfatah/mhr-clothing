<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CommunityImage extends Model
{
    protected $fillable = [
        'image_path',
        'alt_text',
        'caption',
        'width',
        'height',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->isManagedUpload()) {
            return Storage::disk('public')->url($this->image_path);
        }

        return asset($this->image_path);
    }

    public function isManagedUpload(): bool
    {
        return str_starts_with($this->image_path, 'community/');
    }
}
