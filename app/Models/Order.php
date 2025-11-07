<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_customer_id',
        'customer_name',
        'customer_whatsapp',
        'customer_email',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipping_notes',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'status',
        'whatsapp_message_id',
        'whatsapp_sent_at',
        'whatsapp_message',
        'admin_notes',
        'tracking_number',
        'courier',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'whatsapp_sent_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_CONFIRMED = 'payment_confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONTACTED => 'Dihubungi',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_PAYMENT_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAYMENT_CONFIRMED => 'Pembayaran Diterima',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_DELIVERED => 'Diterima',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'gray',
            self::STATUS_CONTACTED => 'blue',
            self::STATUS_CONFIRMED => 'indigo',
            self::STATUS_PAYMENT_PENDING => 'yellow',
            self::STATUS_PAYMENT_CONFIRMED => 'green',
            self::STATUS_PROCESSING => 'purple',
            self::STATUS_SHIPPED => 'cyan',
            self::STATUS_DELIVERED => 'teal',
            self::STATUS_COMPLETED => 'emerald',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
              ->orWhere('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_whatsapp', 'like', "%{$search}%")
              ->orWhere('customer_email', 'like', "%{$search}%");
        });
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
