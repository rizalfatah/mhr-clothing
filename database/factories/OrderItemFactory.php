<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 50000, 500000);
        $quantity = fake()->numberBetween(1, 5);
        $subtotal = $price * $quantity;

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'product_name' => fake()->words(3, true),
            'variant_name' => null,
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
    }

    /**
     * Set a specific order for the item.
     */
    public function forOrder(Order $order): static
    {
        return $this->state(fn(array $attributes) => [
            'order_id' => $order->id,
        ]);
    }

    /**
     * Set a specific product for the item.
     */
    public function forProduct(Product $product): static
    {
        return $this->state(fn(array $attributes) => [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->selling_price,
            'subtotal' => $product->selling_price * $attributes['quantity'],
        ]);
    }

    /**
     * Set custom price and quantity.
     */
    public function withPriceAndQuantity(float $price, int $quantity): static
    {
        return $this->state(fn(array $attributes) => [
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $price * $quantity,
        ]);
    }
}
