<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'value',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_limit_per_user',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Logic
    public function isValidForUser($userId = null, $guestId = null)
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        if ($this->start_date && $this->start_date->gt($now)) {
            return false;
        }
        if ($this->end_date && $this->end_date->lt($now)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->usages()->count() >= $this->usage_limit) {
            return false;
        }

        if ($this->usage_limit_per_user !== null) {
            $query = $this->usages();
            if ($userId) {
                $query->where('user_id', $userId);
            } elseif ($guestId) {
                $query->where('guest_customer_id', $guestId);
            } else {
                // If no user tracking info provided but limit exists, technically we can't check per user, 
                // but safest is to say valid if general limit passed.
                // However, for coupons that require per-user tracking, we might want to restrict.
                // For now, let's assume if tracking info is missing, we check general validity.
                return true;
            }

            if ($query->count() >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount($total)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $total);
        } elseif ($this->type === 'percent') {
            return $total * ($this->value / 100);
        }
        return 0;
    }
}
