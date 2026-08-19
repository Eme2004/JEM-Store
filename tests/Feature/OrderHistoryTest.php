<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $data = []): Product
    {
        $ropa = Category::where('slug', 'ropa')->first()
            ?? Category::create(['name' => 'Ropa', 'slug' => 'ropa']);

        $camisas = Category::where('slug', 'camisas')->first()
            ?? Category::create(['name' => 'Camisas', 'slug' => 'camisas', 'parent_id' => $ropa->id]);

        return Product::create(array_merge([
            'category_id' => $camisas->id,
            'name' => 'JEM Test Shirt',
            'slug' => 'jem-test-shirt',
            'description' => 'Producto de prueba para JEM.',
            'price' => 10000,
            'sale_price' => null,
            'stock' => 20,
            'audience' => 'unisex',
            'image' => null,
            'active' => true,
        ], $data));
    }

    private function createOrder(User $user, array $data = []): Order
    {
        static $sequence = 0;
        $sequence++;

        return Order::create(array_merge([
            'user_id' => $user->id,
            'order_number' => 'JEM-TEST-'.$sequence,
            'tracking_number' => 'TRK-TEST-'.$sequence,
            'subtotal' => 10000,
            'tax' => 1300,
            'shipping' => 2500,
            'total' => 13800,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'status' => 'processing',
            'shipping_name' => $user->name,
            'shipping_email' => $user->email,
            'shipping_phone' => '6019-0694',
            'shipping_address' => 'San José, Costa Rica',
        ], $data));
    }

    private function purchase(User $user, Product $product, int $quantity = 1): Order
    {
        $this->actingAs($user);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);

        // Visita el checkout (como un usuario real) para que se genere el
        // token anti doble-envío que exige checkout.store.
        $this->get(route('checkout.index'));
        $checkoutToken = session('checkout.token');

        $this->post(route('checkout.store'), [
            'shipping_name' => $user->name,
            'shipping_email' => $user->email,
            'shipping_phone' => '6019-0694',
            'shipping_address' => 'San José, Costa Rica',
            'payment_method' => 'card',
            'payment_method_nonce' => 'fake-valid-visa-nonce',
            'checkout_token' => $checkoutToken,
        ]);

        return Order::latest()->first();
    }

    public function test_guest_cannot_access_order_history(): void
    {
        $response = $this->get(route('orders.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_order_detail(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrder($user);

        $response = $this->get(route('orders.show', $order));

        $response->assertRedirect(route('login'));
    }

    public function test_order_history_starts_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertOk();
        $response->assertSee('Aún no tienes pedidos');
    }

    public function test_user_sees_their_own_orders_in_history(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrder($user);

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertOk();
        $response->assertSee($order->order_number);
    }

    public function test_user_does_not_see_another_users_orders_in_history(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $ownerOrder = $this->createOrder($owner);
        $this->createOrder($other);

        $response = $this->actingAs($owner)->get(route('orders.index'));

        $response->assertSee($ownerOrder->order_number);
        $this->assertCount(1, $owner->fresh()->orders);
    }

    public function test_user_cannot_view_another_users_order_detail(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $order = $this->createOrder($owner);

        $response = $this->actingAs($intruder)->get(route('orders.show', $order));

        $response->assertNotFound();
    }

    public function test_order_detail_shows_number_tracking_and_items(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['name' => 'JEM History Tee']);

        $order = $this->purchase($user, $product, 2);

        $response = $this->get(route('orders.show', $order));

        $response->assertOk();
        $response->assertSee($order->order_number);
        $response->assertSee($order->tracking_number);
        $response->assertSee('JEM History Tee');
    }

    public function test_profile_page_shows_recent_orders_after_a_purchase(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $order = $this->purchase($user, $product, 1);

        $response = $this->get(route('profile.show'));

        $response->assertOk();
        $response->assertDontSee('Aún no tienes pedidos');
        $response->assertSee($order->order_number);
    }

    public function test_profile_page_shows_empty_state_without_orders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertOk();
        $response->assertSee('Aún no tienes pedidos');
    }

    public function test_order_history_is_paginated(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 11; $i++) {
            $this->createOrder($user);
        }

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertOk();
        $response->assertViewHas('orders', function ($orders) {
            return $orders->count() === 10 && $orders->total() === 11;
        });
    }
}
