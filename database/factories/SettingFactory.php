<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(),
            'value' => fake()->word(),
            'type' => fake()->randomElement(['text', 'number', 'boolean']),
            'group' => fake()->randomElement(['general', 'whatsapp', 'contact', 'shipping']),
            'description' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the setting is for general configuration.
     */
    public function general(): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => 'general',
        ]);
    }

    /**
     * Indicate that the setting is for WhatsApp configuration.
     */
    public function whatsapp(): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => 'whatsapp',
        ]);
    }

    /**
     * Indicate that the setting is for contact configuration.
     */
    public function contact(): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => 'contact',
        ]);
    }

    /**
     * Indicate that the setting is for shipping configuration.
     */
    public function shipping(): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => 'shipping',
        ]);
    }

    /**
     * Indicate that the setting is a boolean type.
     */
    public function boolean(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'boolean',
            'value' => fake()->randomElement(['0', '1']),
        ]);
    }

    /**
     * Indicate that the setting is a number type.
     */
    public function number(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'number',
            'value' => (string) fake()->numberBetween(0, 100000),
        ]);
    }
}
