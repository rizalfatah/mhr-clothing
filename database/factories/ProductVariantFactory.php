<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sizes = ProductVariant::getAvailableSizes();

        return [
            'product_id' => Product::factory(),
            'size' => fake()->randomElement($sizes),
            'color' => null,
            'sku' => 'SKU-' . strtoupper(fake()->unique()->bothify('??###')),
            'price_adjustment' => fake()->randomElement([0, 5000, 10000, 15000]),
            'stock' => fake()->numberBetween(0, 100),
            'is_available' => true,
        ];
    }

    /**
     * Indicate that the variant is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => 0,
            'is_available' => false,
        ]);
    }

    /**
     * Indicate that the variant has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => fake()->numberBetween(1, 4),
            'is_available' => true,
        ]);
    }

    /**
     * Indicate that the variant is in stock.
     */
    public function inStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => fake()->numberBetween(10, 100),
            'is_available' => true,
        ]);
    }

    /**
     * Indicate that the variant is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_available' => false,
        ]);
    }

    /**
     * Set specific size.
     */
    public function size(string $size): static
    {
        return $this->state(fn(array $attributes) => [
            'size' => $size,
        ]);
    }
}
