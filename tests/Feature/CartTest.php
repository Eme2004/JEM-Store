<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(
        string $name,
        string $slug,
        ?int $parentId = null
    ): Category {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId,
        ]);
    }

    private function createProduct(array $data = []): Product
    {
        $ropa = Category::where('slug', 'ropa')->first();

        if (! $ropa) {
            $ropa = $this->createCategory('Ropa', 'ropa');
        }

        $camisas = Category::where('slug', 'camisas')->first();

        if (! $camisas) {
            $camisas = $this->createCategory(
                'Camisas',
                'camisas',
                $ropa->id
            );
        }

        return Product::create(array_merge([
            'category_id' => $camisas->id,
            'name' => 'JEM Test Shirt',
            'slug' => 'jem-test-shirt',
            'description' => 'Producto de prueba para JEM.',
            'price' => 30000,
            'sale_price' => null,
            'stock' => 10,
            'audience' => 'unisex',
            'image' => null,
            'active' => true,
        ], $data));
    }

    public function test_cart_starts_empty(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertViewHas('items', function ($items) {
            return $items->isEmpty();
        });
    }

    public function test_active_product_with_stock_can_be_added(): void
    {
        $product = $this->createProduct();

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('status');

        $this->get(route('cart.index'))
            ->assertSee($product->name);
    }

    public function test_quantity_is_stored_correctly_in_session(): void
    {
        $product = $this->createProduct();

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $cart = session('cart');

        $this->assertEquals($product->id, $cart[$product->id]['product_id']);
        $this->assertEquals(3, $cart[$product->id]['quantity']);
    }

    public function test_adding_the_same_product_again_updates_its_quantity(): void
    {
        $product = $this->createProduct([
            'stock' => 10,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $cart = session('cart');

        $this->assertEquals(5, $cart[$product->id]['quantity']);
    }

    public function test_out_of_stock_product_cannot_be_added(): void
    {
        $product = $this->createProduct([
            'stock' => 0,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error');
        $this->assertEmpty(session('cart', []));
    }

    public function test_inactive_product_cannot_be_added(): void
    {
        $product = $this->createProduct([
            'active' => false,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error');
        $this->assertEmpty(session('cart', []));
    }

    public function test_cannot_exceed_available_stock(): void
    {
        $product = $this->createProduct([
            'stock' => 5,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 6,
        ]);

        $response->assertSessionHas('error');
        $this->assertEmpty(session('cart', []));
    }

    public function test_cannot_use_zero_quantity(): void
    {
        $product = $this->createProduct();

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertEmpty(session('cart', []));
    }

    public function test_cannot_use_negative_quantity(): void
    {
        $product = $this->createProduct();

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => -1,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertEmpty(session('cart', []));
    }

    public function test_quantity_can_be_updated(): void
    {
        $product = $this->createProduct([
            'stock' => 10,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->patch(route('cart.update', $product), [
            'quantity' => 4,
        ]);

        $response->assertRedirect(route('cart.index'));

        $cart = session('cart');

        $this->assertEquals(4, $cart[$product->id]['quantity']);
    }

    public function test_quantity_cannot_be_updated_above_stock(): void
    {
        $product = $this->createProduct([
            'stock' => 5,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->patch(route('cart.update', $product), [
            'quantity' => 10,
        ]);

        $response->assertSessionHas('error');

        $cart = session('cart');

        $this->assertEquals(2, $cart[$product->id]['quantity']);
    }

    public function test_product_can_be_removed(): void
    {
        $product = $this->createProduct();

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->delete(route('cart.destroy', $product));

        $response->assertRedirect(route('cart.index'));
        $this->assertArrayNotHasKey($product->id, session('cart', []));
    }

    public function test_cart_can_be_cleared(): void
    {
        $productA = $this->createProduct([
            'name' => 'Producto A',
            'slug' => 'producto-a',
        ]);

        $productB = $this->createProduct([
            'name' => 'Producto B',
            'slug' => 'producto-b',
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $productA->id,
            'quantity' => 1,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $productB->id,
            'quantity' => 1,
        ]);

        $response = $this->delete(route('cart.clear'));

        $response->assertRedirect(route('cart.index'));
        $this->assertEmpty(session('cart', []));
    }

    public function test_uses_sale_price_when_product_is_on_sale(): void
    {
        $product = $this->createProduct([
            'price' => 50000,
            'sale_price' => 35000,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('cart.index'));

        $response->assertViewHas('subtotal', 35000.0);
    }

    public function test_calculates_subtotal_correctly(): void
    {
        $productA = $this->createProduct([
            'name' => 'Producto A',
            'slug' => 'producto-a',
            'price' => 10000,
        ]);

        $productB = $this->createProduct([
            'name' => 'Producto B',
            'slug' => 'producto-b',
            'price' => 20000,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $productA->id,
            'quantity' => 2,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $productB->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('cart.index'));

        // 2 x 10000 + 1 x 20000 = 40000
        $response->assertViewHas('subtotal', 40000.0);
    }

    public function test_calculates_tax_correctly(): void
    {
        $product = $this->createProduct([
            'price' => 10000,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('cart.index'));

        // 10000 x 0.13 = 1300
        $response->assertViewHas('tax', 1300.0);
    }

    public function test_calculates_shipping_cost_when_below_free_shipping_threshold(): void
    {
        $product = $this->createProduct([
            'price' => 10000,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('cart.index'));

        $response->assertViewHas('shipping', (float) config('store.shipping_cost'));
    }

    public function test_applies_free_shipping_at_threshold(): void
    {
        $product = $this->createProduct([
            'price' => 35000,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('cart.index'));

        $response->assertViewHas('shipping', 0.0);
    }

    public function test_calculates_total_correctly(): void
    {
        $product = $this->createProduct([
            'price' => 10000,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get(route('cart.index'));

        // subtotal 10000 + tax 1300 + shipping 2500 = 13800
        $response->assertViewHas('total', 13800.0);
    }

    public function test_cart_page_responds_correctly(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertViewIs('cart.index');
    }
}
