<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
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

    public function test_catalog_is_public(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertOk();
    }

    public function test_catalog_shows_active_products(): void
    {
        $product = $this->createProduct();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_catalog_does_not_show_inactive_products(): void
    {
        $product = $this->createProduct([
            'active' => false,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertDontSee($product->name);
    }

    public function test_product_detail_can_be_opened_by_slug(): void
    {
        $product = $this->createProduct();

        $response = $this->get(
            route('products.show', $product)
        );

        $response->assertOk();
        $response->assertSee($product->name);
        $response->assertSee($product->description);
    }

    public function test_inactive_product_returns_404(): void
    {
        $product = $this->createProduct([
            'active' => false,
        ]);

        $response = $this->get(
            route('products.show', $product)
        );

        $response->assertNotFound();
    }

    public function test_hombre_filter_includes_hombre_and_unisex(): void
    {
        $this->createProduct([
            'name' => 'Producto Hombre',
            'slug' => 'producto-hombre',
            'audience' => 'hombre',
        ]);

        $this->createProduct([
            'name' => 'Producto Unisex',
            'slug' => 'producto-unisex',
            'audience' => 'unisex',
        ]);

        $this->createProduct([
            'name' => 'Producto Mujer',
            'slug' => 'producto-mujer',
            'audience' => 'mujer',
        ]);

        $response = $this->get(
            route('products.index', [
                'audience' => 'hombre',
            ])
        );

        $response->assertSee('Producto Hombre');
        $response->assertSee('Producto Unisex');
        $response->assertDontSee('Producto Mujer');
    }

    public function test_mujer_filter_includes_mujer_and_unisex(): void
    {
        $this->createProduct([
            'name' => 'Producto Mujer',
            'slug' => 'producto-mujer',
            'audience' => 'mujer',
        ]);

        $this->createProduct([
            'name' => 'Producto Unisex',
            'slug' => 'producto-unisex',
            'audience' => 'unisex',
        ]);

        $this->createProduct([
            'name' => 'Producto Hombre',
            'slug' => 'producto-hombre',
            'audience' => 'hombre',
        ]);

        $response = $this->get(
            route('products.index', [
                'audience' => 'mujer',
            ])
        );

        $response->assertSee('Producto Mujer');
        $response->assertSee('Producto Unisex');
        $response->assertDontSee('Producto Hombre');
    }

    public function test_category_filter_works(): void
    {
        $ropa = $this->createCategory('Ropa', 'ropa');

        $camisas = $this->createCategory(
            'Camisas',
            'camisas',
            $ropa->id
        );

        $pantalones = $this->createCategory(
            'Pantalones',
            'pantalones',
            $ropa->id
        );

        Product::create([
            'category_id' => $camisas->id,
            'name' => 'JEM Camisa',
            'slug' => 'jem-camisa',
            'description' => 'Camisa de prueba.',
            'price' => 30000,
            'stock' => 10,
            'audience' => 'unisex',
            'active' => true,
        ]);

        Product::create([
            'category_id' => $pantalones->id,
            'name' => 'JEM Pantalón',
            'slug' => 'jem-pantalon',
            'description' => 'Pantalón de prueba.',
            'price' => 35000,
            'stock' => 10,
            'audience' => 'unisex',
            'active' => true,
        ]);

        $response = $this->get(
            route('products.index', [
                'category' => 'camisas',
            ])
        );

        $response->assertSee('JEM Camisa');
        $response->assertDontSee('JEM Pantalón');
    }

    public function test_sale_filter_only_shows_products_on_sale(): void
    {
        $this->createProduct([
            'name' => 'Producto en Oferta',
            'slug' => 'producto-oferta',
            'sale_price' => 25000,
        ]);

        $this->createProduct([
            'name' => 'Producto Normal',
            'slug' => 'producto-normal',
            'sale_price' => null,
        ]);

        $response = $this->get(
            route('products.index', [
                'sale' => 1,
            ])
        );

        $response->assertSee('Producto en Oferta');
        $response->assertDontSee('Producto Normal');
    }

    public function test_search_finds_product_by_name(): void
    {
        $this->createProduct([
            'name' => 'JEM Oxford Especial',
            'slug' => 'jem-oxford-especial',
        ]);

        $this->createProduct([
            'name' => 'JEM Producto Diferente',
            'slug' => 'jem-producto-diferente',
        ]);

        $response = $this->get(
            route('products.index', [
                'search' => 'Oxford',
            ])
        );

        $response->assertSee('JEM Oxford Especial');
        $response->assertDontSee('JEM Producto Diferente');
    }

    public function test_product_view_creates_recent_products_cookie(): void
    {
        $product = $this->createProduct();

        $response = $this->get(
            route('products.show', $product)
        );

        $response->assertOk();
        $response->assertCookie('jem_recent_products');
    }
}
