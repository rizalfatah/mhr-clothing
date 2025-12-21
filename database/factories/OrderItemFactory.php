<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 50000, 300000);
        $quantity = fake()->numberBetween(1, 5);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'product_name' => fake()->words(3, true),
            'variant_name' => fake()->randomElement(['Small', 'Medium', 'Large', 'XL', 'XXL']),
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $price * $quantity,
        ];
    }

    /**
     * Indicate order item for a specific order.
     */
    public function forOrder(Order $order): static
    {
        return $this->state(fn(array $attributes) => [
            'order_id' => $order->id,
        ]);
    }

    /**
     * Indicate order item for a specific product.
     */
    public function forProduct(Product $product, ?ProductVariant $variant = null): static
    {
        return $this->state(fn(array $attributes) => [
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id ?? ProductVariant::factory(['product_id' => $product->id]),
            'product_name' => $product->name,
            'price' => $variant ? $variant->final_price : $product->selling_price,
        ]);
    }
}
