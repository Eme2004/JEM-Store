<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
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
        return [
            'user_id' => User::factory(),
            'order_number' => 'JEM-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
            'tracking_number' => 'TRK-'.strtoupper(Str::random(8)),
            'subtotal' => 10000,
            'discount' => 0,
            'tax' => 1300,
            'shipping' => 0,
            'total' => 11300,
            'payment_method' => 'card',
            'payment_gateway' => 'simulated',
            'payment_environment' => 'sandbox',
            'payment_status' => 'paid',
            'status' => 'processing',
            'shipping_name' => fake()->name(),
            'shipping_email' => fake()->safeEmail(),
            'shipping_phone' => '6019-0694',
            'shipping_address' => 'San José, Costa Rica',
        ];
    }
}
