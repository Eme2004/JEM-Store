<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SyncProductImagesTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(string $name, string $slug): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => null,
        ]);
    }

    private function createProduct(array $data = []): Product
    {
        $category = Category::where('slug', 'camisas')->first()
            ?? $this->createCategory('Camisas', 'camisas');

        return Product::create(array_merge([
            'category_id' => $category->id,
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

    public function test_it_sets_image_when_a_webp_file_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/jem-test-shirt.webp', 'contenido');

        $product = $this->createProduct();

        $this->artisan('products:sync-images')
            ->expectsOutputToContain('[OK] jem-test-shirt -> products/jem-test-shirt.webp')
            ->assertExitCode(0);

        $this->assertSame('products/jem-test-shirt.webp', $product->fresh()->image);
    }

    public function test_it_prefers_webp_over_other_extensions(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/jem-test-shirt.png', 'contenido-png');
        Storage::disk('public')->put('products/jem-test-shirt.webp', 'contenido-webp');

        $product = $this->createProduct();

        $this->artisan('products:sync-images')->assertExitCode(0);

        $this->assertSame('products/jem-test-shirt.webp', $product->fresh()->image);
    }

    public function test_it_skips_products_without_a_matching_image_and_does_not_touch_existing_image(): void
    {
        Storage::fake('public');

        $product = $this->createProduct([
            'slug' => 'jem-sin-imagen',
            'image' => 'products/jem-sin-imagen.webp',
        ]);

        $this->artisan('products:sync-images')
            ->expectsOutputToContain('[SKIP] jem-sin-imagen -> imagen no encontrada')
            ->assertExitCode(0);

        $this->assertSame('products/jem-sin-imagen.webp', $product->fresh()->image);
    }

    public function test_it_does_not_modify_stock_price_or_other_fields(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/jem-test-shirt.webp', 'contenido');

        $product = $this->createProduct([
            'name' => 'Nombre Original',
            'price' => 45900,
            'sale_price' => 39900,
            'stock' => 7,
            'active' => true,
        ]);

        $this->artisan('products:sync-images')->assertExitCode(0);

        $fresh = $product->fresh();

        $this->assertSame('Nombre Original', $fresh->name);
        $this->assertEquals(45900, $fresh->price);
        $this->assertEquals(39900, $fresh->sale_price);
        $this->assertSame(7, $fresh->stock);
        $this->assertTrue($fresh->active);
    }

    public function test_it_does_not_overwrite_an_image_uploaded_from_the_admin_panel(): void
    {
        Storage::fake('public');
        // El archivo curado del catálogo SÍ existe...
        Storage::disk('public')->put('products/jem-test-shirt.webp', 'catalogo');
        // ...pero el producto ya tiene una foto subida manualmente desde el admin.
        Storage::disk('public')->put('products/uploads/foto-admin.jpg', 'admin');

        $product = $this->createProduct([
            'image' => 'products/uploads/foto-admin.jpg',
        ]);

        $this->artisan('products:sync-images')
            ->expectsOutputToContain('[SKIP] jem-test-shirt -> imagen gestionada manualmente, no se modifica')
            ->assertExitCode(0);

        $this->assertSame('products/uploads/foto-admin.jpg', $product->fresh()->image);
    }

    public function test_it_syncs_multiple_products_independently(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/jem-uno.jpg', 'contenido');

        $conImagen = $this->createProduct([
            'slug' => 'jem-uno',
            'name' => 'JEM Uno',
        ]);

        $sinImagen = $this->createProduct([
            'slug' => 'jem-dos',
            'name' => 'JEM Dos',
        ]);

        $this->artisan('products:sync-images')->assertExitCode(0);

        $this->assertSame('products/jem-uno.jpg', $conImagen->fresh()->image);
        $this->assertNull($sinImagen->fresh()->image);
    }
}
