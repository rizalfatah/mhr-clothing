<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    // Size constants
    const SIZE_SMALL = 'Small';
    const SIZE_MEDIUM = 'Medium';
    const SIZE_LARGE = 'Large';
    const SIZE_XL = 'XL';
    const SIZE_XXL = 'XXL';

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'sku',
        'price_adjustment',
        'stock',
        'is_available',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_available' => 'boolean',
        'stock' => 'integer',
    ];

    /**
     * Get all available sizes
     */
    public static function getAvailableSizes(): array
    {
        return [
            self::SIZE_SMALL,
            self::SIZE_MEDIUM,
            self::SIZE_LARGE,
            self::SIZE_XL,
            self::SIZE_XXL,
        ];
    }

    /**
     * Get the product that owns this variant
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if variant has stock available
     */
    public function hasStock(int $quantity = 1): bool
    {
        return $this->is_available && $this->stock >= $quantity;
    }

    /**
     * Decrement stock
     */
    public function decrementStock(int $quantity): bool
    {
        if (!$this->hasStock($quantity)) {
            return false;
        }

        $this->stock -= $quantity;

        // Auto-disable if stock reaches 0
        if ($this->stock <= 0) {
            $this->is_available = false;
            $this->stock = 0;
        }

        $this->save();
        return true;
    }

    /**
     * Increment stock
     */
    public function incrementStock(int $quantity): void
    {
        $this->stock += $quantity;

        // Re-enable if stock is added
        if ($this->stock > 0 && !$this->is_available) {
            $this->is_available = true;
        }

        $this->save();
    }

    /**
     * Get the final price including adjustment
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->product->selling_price + $this->price_adjustment;
    }

    /**
     * Scope to only available variants
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }
}
