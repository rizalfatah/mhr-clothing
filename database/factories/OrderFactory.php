<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50000, 500000);
        $shippingCost = fake()->randomFloat(2, 10000, 50000);
        $discount = fake()->optional(0.3)->randomFloat(2, 5000, 20000) ?? 0;

        return [
            'order_number' => $this->generateOrderNumber(),
            'user_id' => fake()->optional(0.7)->randomElement([User::factory()]),
            'guest_customer_id' => fake()->optional()->uuid(),
            'customer_name' => fake()->name(),
            'customer_whatsapp' => '62' . fake()->numerify('81########'),
            'customer_email' => fake()->optional()->safeEmail(),
            'shipping_address' => fake()->address(),
            'shipping_city' => fake()->city(),
            'shipping_province' => fake()->state(),
            'shipping_postal_code' => fake()->postcode(),
            'shipping_notes' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $subtotal + $shippingCost - $discount,
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
     * Generate unique order number.
     */
    private function generateOrderNumber(): string
    {
        return 'MHR-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    /**
     * Indicate order status.
     */
    public function status(string $status): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * Indicate order is pending.
     */
    public function pending(): static
    {
        return $this->status(Order::STATUS_PENDING);
    }

    /**
     * Indicate order is confirmed.
     */
    public function confirmed(): static
    {
        return $this->status(Order::STATUS_CONFIRMED);
    }

    /**
     * Indicate order is shipped.
     */
    public function shipped(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Order::STATUS_SHIPPED,
            'courier' => fake()->randomElement(['JNE', 'J&T', 'SiCepat', 'AnterAja']),
            'tracking_number' => fake()->bothify('##??########'),
        ]);
    }

    /**
     * Indicate order is completed.
     */
    public function completed(): static
    {
        return $this->status(Order::STATUS_COMPLETED);
    }

    /**
     * Indicate order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Order::STATUS_CANCELLED,
            'admin_notes' => fake()->optional()->sentence(),
        ]);
    }

    /**
     * Indicate order has a user.
     */
    public function forUser(?User $user = null): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user?->id ?? User::factory(),
            'guest_customer_id' => null,
        ]);
    }

    /**
     * Indicate order is for a guest.
     */
    public function forGuest(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => null,
            'guest_customer_id' => fake()->uuid(),
        ]);
    }
}
