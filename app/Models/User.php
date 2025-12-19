<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp_number',
        'password',
        'role',
        'account_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the cart items for the user
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the addresses for the user
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the default address for the user
     */
    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * Get the orders for the user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    /**
     * Get the account status label
     */
    public function getAccountStatusLabelAttribute(): string
    {
        return match ($this->account_status) {
            'active' => 'Active',
            'suspended' => 'Suspended',
            'banned' => 'Banned',
            default => ucfirst($this->account_status),
        };
    }

    /**
     * Get the account status color for UI
     */
    public function getAccountStatusColorAttribute(): string
    {
        return match ($this->account_status) {
            'active' => 'green',
            'suspended' => 'yellow',
            'banned' => 'red',
            default => 'gray',
        };
    }
}
