<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100000, 1000000);
        $shippingCost = fake()->randomFloat(2, 10000, 50000);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.1);
        $total = $subtotal + $shippingCost - $discount;

        return [
            'order_number' => 'ORD-' . strtoupper(fake()->unique()->bothify('??####??')),
            'user_id' => null,
            'guest_customer_id' => null,
            'customer_name' => fake()->name(),
            'customer_whatsapp' => fake()->numerify('08##########'),
            'customer_email' => fake()->safeEmail(),
            'shipping_address' => fake()->streetAddress(),
            'shipping_city' => fake()->city(),
            'shipping_province' => fake()->state(),
            'shipping_postal_code' => fake()->postcode(),
            'shipping_notes' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $total,
            'status' => Order::STATUS_PENDING,
            'whatsapp_message_id' => null,
            'whatsapp_sent_at' => null,
            'whatsapp_message' => null,
            'admin_notes' => null,
            'tracking_number' => null,
            'courier' => null,
        ];
    }

    /**
     * Indicate that the order belongs to a user.
     */
    public function forUser(User $user = null): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user ? $user->id : User::factory(),
        ]);
    }

    /**
     * Indicate that the order is for a guest.
     */
    public function guest(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => null,
            'guest_customer_id' => fake()->uuid(),
        ]);
    }

    /**
     * Set the order status.
     */
    public function status(string $status): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * Indicate that the order is confirmed.
     */
    public function confirmed(): static
    {
        return $this->status(Order::STATUS_CONFIRMED);
    }

    /**
     * Indicate that the order is shipped.
     */
    public function shipped(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Order::STATUS_SHIPPED,
            'tracking_number' => fake()->bothify('??########'),
            'courier' => fake()->randomElement(['JNE', 'J&T', 'SiCepat', 'AnterAja']),
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->status(Order::STATUS_COMPLETED);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->status(Order::STATUS_CANCELLED);
    }

    /**
     * Set custom totals for testing.
     */
    public function withTotals(float $subtotal, float $shippingCost, float $discount): static
    {
        return $this->state(fn(array $attributes) => [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $subtotal + $shippingCost - $discount,
        ]);
    }
}
