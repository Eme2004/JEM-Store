<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $data = []): Product
    {
        $ropa = Category::where('slug', 'ropa')->first();

        if (! $ropa) {
            $ropa = Category::create(['name' => 'Ropa', 'slug' => 'ropa']);
        }

        $camisas = Category::where('slug', 'camisas')->first();

        if (! $camisas) {
            $camisas = Category::create([
                'name' => 'Camisas',
                'slug' => 'camisas',
                'parent_id' => $ropa->id,
            ]);
        }

        return Product::create(array_merge([
            'category_id' => $camisas->id,
            'name' => 'JEM Test Shirt',
            'slug' => 'jem-test-shirt',
            'description' => 'Producto de prueba para JEM.',
            'price' => 10000,
            'sale_price' => null,
            'stock' => 10,
            'audience' => 'unisex',
            'image' => null,
            'active' => true,
        ], $data));
    }

    private function addToCart(Product $product, int $quantity = 1): void
    {
        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    private function validShippingData(array $overrides = []): array
    {
        return array_merge([
            'shipping_name' => 'Jean Pérez',
            'shipping_email' => 'jean@example.com',
            'shipping_phone' => '6019-0694',
            'shipping_address' => 'San José, Costa Rica',
            'payment_method' => 'card',
            'card_holder' => 'Jean Perez',
            'card_number' => '4111111111111111',
            'card_expiry' => '12/29',
            'card_cvv' => '123',
        ], $overrides);
    }

    public function test_checkout_requires_authentication(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_calculates_subtotal(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000]);

        $this->actingAs($user);
        $this->addToCart($product, 2);

        $response = $this->get(route('checkout.index'));

        $response->assertOk();
        $response->assertViewHas('subtotal', 20000.0);
    }

    public function test_checkout_calculates_tax(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000]);

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $response = $this->get(route('checkout.index'));

        $response->assertViewHas('tax', 1300.0);
    }

    public function test_checkout_calculates_shipping(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000]);

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $response = $this->get(route('checkout.index'));

        $response->assertViewHas('shipping', (float) config('store.shipping_cost'));
    }

    public function test_checkout_applies_free_shipping(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 40000]);

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $response = $this->get(route('checkout.index'));

        $response->assertViewHas('shipping', 0.0);
    }

    public function test_checkout_creates_an_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000, 'stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 2);

        $response = $this->post(route('checkout.store'), $this->validShippingData());

        $order = Order::first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order));

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(20000, (float) $order->subtotal);
        $this->assertEquals('card', $order->payment_method);
        $this->assertEquals('paid', $order->payment_status);
    }

    public function test_checkout_creates_order_items(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000, 'stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 2);

        $this->post(route('checkout.store'), $this->validShippingData());

        $order = Order::first();

        $this->assertCount(1, $order->items);

        $item = $order->items->first();

        $this->assertEquals($product->id, $item->product_id);
        $this->assertEquals($product->name, $item->product_name);
        $this->assertEquals(2, $item->quantity);
        $this->assertEquals(20000, (float) $item->subtotal);
    }

    public function test_checkout_decreases_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 3);

        $this->post(route('checkout.store'), $this->validShippingData());

        $this->assertEquals(2, $product->fresh()->stock);
    }

    public function test_checkout_generates_order_and_tracking_numbers(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $this->post(route('checkout.store'), $this->validShippingData());

        $order = Order::first();

        $this->assertNotEmpty($order->order_number);
        $this->assertNotEmpty($order->tracking_number);
        $this->assertStringStartsWith('JEM-', $order->order_number);
        $this->assertStringStartsWith('TRK-', $order->tracking_number);
    }

    public function test_checkout_empties_the_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $this->post(route('checkout.store'), $this->validShippingData());

        $this->assertEmpty(session('cart', []));
    }

    public function test_checkout_does_not_store_card_data(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $this->post(route('checkout.store'), $this->validShippingData());

        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');

        $this->assertNotContains('card_number', $columns);
        $this->assertNotContains('card_cvv', $columns);
        $this->assertNotContains('card_expiry', $columns);
    }

    public function test_checkout_fails_with_empty_cart(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('checkout.store'), $this->validShippingData());

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 2]);

        $this->actingAs($user);
        $this->addToCart($product, 2);

        // El stock baja después de agregarlo al carrito.
        $product->update(['stock' => 1]);

        $response = $this->post(route('checkout.store'), $this->validShippingData());

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(1, $product->fresh()->stock);
    }

    public function test_checkout_requires_card_fields_when_paying_with_card(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'card_number' => null,
        ]));

        $response->assertSessionHasErrors('card_number');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_accepts_paypal_without_card_fields(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'payment_method' => 'paypal',
            'card_holder' => null,
            'card_number' => null,
            'card_expiry' => null,
            'card_cvv' => null,
        ]));

        $order = Order::first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order));
        $this->assertEquals('paypal', $order->payment_method);
    }

    public function test_success_page_requires_authentication(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);
        $this->post(route('checkout.store'), $this->validShippingData());

        $order = Order::first();
        $this->post(route('logout'));

        $response = $this->get(route('checkout.success', $order));

        $response->assertRedirect(route('login'));
    }

    public function test_success_page_cannot_be_viewed_by_another_user(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($owner);
        $this->addToCart($product, 1);
        $this->post(route('checkout.store'), $this->validShippingData());

        $order = Order::first();

        $response = $this->actingAs($intruder)
            ->get(route('checkout.success', $order));

        $response->assertNotFound();
    }

    public function test_success_page_shows_order_details(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['name' => 'JEM Special Tee']);

        $this->actingAs($user);
        $this->addToCart($product, 1);
        $this->post(route('checkout.store'), $this->validShippingData());

        $order = Order::first();

        $response = $this->get(route('checkout.success', $order));

        $response->assertOk();
        $response->assertSee($order->order_number);
        $response->assertSee($order->tracking_number);
        $response->assertSee('JEM Special Tee');
    }
}
