<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => 'products/' . fake()->uuid() . '.jpg',
            'is_primary' => false,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that this is the primary image.
     */
    public function primary(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_primary' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * Set a specific product for the image.
     */
    public function forProduct(Product $product): static
    {
        return $this->state(fn(array $attributes) => [
            'product_id' => $product->id,
        ]);
    }
}
