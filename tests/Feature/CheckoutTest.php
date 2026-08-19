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

    /**
     * Visita el checkout (como haría un usuario real) para que el
     * controlador genere y guarde en sesión el token anti doble-envío, y
     * lo devuelve para usarlo en el POST de la prueba.
     */
    private function checkoutToken(): ?string
    {
        $this->get(route('checkout.index'));

        return session('checkout.token');
    }

    private function validShippingData(array $overrides = []): array
    {
        return array_merge([
            'shipping_name' => 'Jean Pérez',
            'shipping_email' => 'jean@example.com',
            'shipping_phone' => '6019-0694',
            'shipping_address' => 'San José, Costa Rica',
            'payment_method' => 'card',
            'payment_method_nonce' => 'fake-valid-visa-nonce',
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

    public function test_checkout_index_exposes_braintree_client_token(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $response = $this->get(route('checkout.index'));

        $response->assertViewHas('braintreeClientToken');
        $response->assertViewHas('checkoutToken');
    }

    public function test_checkout_creates_an_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000, 'stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 2);

        $token = $this->checkoutToken();
        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $order = Order::first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order));

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(20000, (float) $order->subtotal);
        $this->assertEquals('card', $order->payment_method);
        $this->assertEquals('paid', $order->payment_status);
    }

    public function test_checkout_stores_gateway_transaction_details_for_card_payments(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000, 'stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $order = Order::first();

        $this->assertNotNull($order);
        $this->assertStringStartsWith('braintree', $order->payment_gateway);
        $this->assertEquals('sandbox', $order->payment_environment);
        $this->assertNotEmpty($order->transaction_id);
        $this->assertEquals('Visa', $order->card_brand);
        $this->assertEquals('1111', $order->card_last4);
    }

    public function test_checkout_rejects_a_declined_card_and_creates_no_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();
        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
            'payment_method_nonce' => 'fake-processor-declined-visa-nonce',
        ]));

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(5, $product->fresh()->stock);
    }

    public function test_checkout_prevents_double_submit_with_the_same_token(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();

        $first = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));
        $first->assertSessionHas('status');

        // Reenviar exactamente el mismo formulario (doble clic / doble POST).
        $this->addToCart($product, 1);
        $second = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $second->assertRedirect(route('cart.index'));
        $second->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_checkout_rejects_a_missing_or_invalid_token(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);
        $this->checkoutToken();

        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => 'token-invalido',
        ]));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_creates_order_items(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 10000, 'stock' => 5]);

        $this->actingAs($user);
        $this->addToCart($product, 2);

        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

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

        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $this->assertEquals(2, $product->fresh()->stock);
    }

    public function test_checkout_generates_order_and_tracking_numbers(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

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

        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $this->assertEmpty(session('cart', []));
    }

    public function test_checkout_does_not_store_card_data(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');

        $this->assertNotContains('card_number', $columns);
        $this->assertNotContains('card_cvv', $columns);
        $this->assertNotContains('card_expiry', $columns);
        $this->assertNotContains('payment_method_nonce', $columns);
    }

    public function test_checkout_fails_with_empty_cart(): void
    {
        $user = User::factory()->create();

        // El carrito está vacío: el controlador lo rechaza antes de
        // siquiera revisar el token de doble-envío.
        $response = $this->actingAs($user)->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => 'cualquier-token',
        ]));

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

        // El token se obtiene mientras el stock todavía alcanza (así como
        // un usuario real vería la página de checkout antes de que otro
        // comprador agote el stock); el stock baja recién después.
        $token = $this->checkoutToken();
        $product->update(['stock' => 1]);

        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(1, $product->fresh()->stock);
    }

    public function test_checkout_requires_nonce_when_paying_with_card(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();
        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
            'payment_method_nonce' => null,
        ]));

        $response->assertSessionHasErrors('payment_method_nonce');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_accepts_paypal_without_a_card_nonce(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);

        $token = $this->checkoutToken();
        $response = $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
            'payment_method' => 'paypal',
            'payment_method_nonce' => null,
        ]));

        $order = Order::first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order));
        $this->assertEquals('paypal', $order->payment_method);
        $this->assertEquals('simulated', $order->payment_gateway);
    }

    public function test_success_page_requires_authentication(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);
        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

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
        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

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
        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $order = Order::first();

        $response = $this->get(route('checkout.success', $order));

        $response->assertOk();
        $response->assertSee($order->order_number);
        $response->assertSee($order->tracking_number);
        $response->assertSee('JEM Special Tee');
    }

    public function test_success_page_shows_braintree_sandbox_transaction_details(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user);
        $this->addToCart($product, 1);
        $token = $this->checkoutToken();
        $this->post(route('checkout.store'), $this->validShippingData([
            'checkout_token' => $token,
        ]));

        $order = Order::first();

        $response = $this->get(route('checkout.success', $order));

        $response->assertOk();
        $response->assertSee('Braintree');
        $response->assertSee('SANDBOX');
        $response->assertSee($order->transaction_id);
        $response->assertSee('No se procesó dinero real');
    }
}
