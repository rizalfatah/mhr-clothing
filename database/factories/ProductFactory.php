<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'details' => fake()->paragraphs(3, true),
            'tokopedia_url' => fake()->optional()->url(),
            'shopee_url' => fake()->optional()->url(),
            'tiktok_url' => fake()->optional()->url(),
            'original_price' => fake()->randomFloat(2, 100000, 500000),
            'selling_price' => fake()->randomFloat(2, 80000, 400000),
            'weight' => fake()->numberBetween(200, 2000),
            'rating' => fake()->randomFloat(1, 4.0, 5.0),
            'total_reviews' => fake()->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
            'sort_order' => fake()->numberBetween(0, 100),
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
}
