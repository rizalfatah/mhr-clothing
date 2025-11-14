<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $originalPrice = fake()->randomFloat(2, 50000, 500000);
        $sellingPrice = $originalPrice - fake()->randomFloat(2, 0, $originalPrice * 0.3);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'details' => fake()->paragraphs(3, true),
            'tokopedia_url' => fake()->optional()->url(),
            'shopee_url' => fake()->optional()->url(),
            'tiktok_url' => fake()->optional()->url(),
            'original_price' => $originalPrice,
            'selling_price' => $sellingPrice,
            'weight' => fake()->numberBetween(100, 2000),
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
            'total_reviews' => fake()->numberBetween(0, 1000),
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Set a specific category for the product.
     */
    public function forCategory(Category $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $category->id,
        ]);
    }
}
