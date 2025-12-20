<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'country',
        'region',
        'city',
        'latitude',
        'longitude',
        'started_at',
        'last_activity_at',
        'ended_at',
        'duration_minutes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'ended_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user that owns the session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active sessions
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }

    /**
     * Get formatted location string
     */
    public function getLocationAttribute(): string
    {
        $parts = array_filter([
            $this->city,
            $this->region,
            $this->country,
        ]);

        return implode(', ', $parts) ?: 'Unknown';
    }

    /**
     * Calculate and update session duration
     */
    public function calculateDuration(): void
    {
        if ($this->ended_at) {
            $this->duration_minutes = $this->started_at->diffInMinutes($this->ended_at);
            $this->save();
        }
    }

    /**
     * Check if session is currently active
     */
    public function isActive(): bool
    {
        return $this->ended_at === null;
    }
}
