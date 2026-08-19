<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        $ropa = Category::firstOrCreate(['slug' => 'ropa'], ['name' => 'Ropa', 'parent_id' => null]);

        return Category::firstOrCreate(['slug' => 'camisas'], ['name' => 'Camisas', 'parent_id' => $ropa->id]);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function productPayload(array $overrides = []): array
    {
        return array_merge([
            'category_id' => $this->createCategory()->id,
            'name' => 'JEM Test Shirt',
            'description' => 'Producto de prueba.',
            'price' => 30000,
            'sale_price' => null,
            'stock' => 10,
            'audience' => 'unisex',
            'active' => '1',
        ], $overrides);
    }

    public function test_guest_cannot_access_admin_products(): void
    {
        $response = $this->get(route('admin.products.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_user_gets_403(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.products.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_products_index(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.products.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_product_without_image(): void
    {
        $response = $this->actingAs($this->admin())
            ->post(route('admin.products.store'), $this->productPayload());

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'JEM Test Shirt',
            'image' => null,
        ]);
    }

    public function test_admin_can_create_product_with_image_stored_under_uploads(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('foto.jpg', 800, 1000);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.products.store'), $this->productPayload(['image' => $file]));

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'JEM Test Shirt')->firstOrFail();

        $this->assertStringStartsWith('products/uploads/', $product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_admin_create_rejects_non_image_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('archivo.txt', 10, 'text/plain');

        $response = $this->actingAs($this->admin())
            ->post(route('admin.products.store'), $this->productPayload(['image' => $file]));

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('products', ['name' => 'JEM Test Shirt']);
    }

    public function test_admin_updating_with_new_image_deletes_old_uploaded_image(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.products.store'), $this->productPayload([
            'image' => UploadedFile::fake()->image('primera.jpg', 800, 1000),
        ]));

        $product = Product::where('name', 'JEM Test Shirt')->firstOrFail();
        $oldPath = $product->image;
        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($admin)->put(route('admin.products.update', $product), array_merge(
            $this->productPayload(['category_id' => $product->category_id]),
            ['image' => UploadedFile::fake()->image('segunda.jpg', 800, 1000)]
        ));

        $product->refresh();

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($product->image);
        $this->assertNotEquals($oldPath, $product->image);
    }

    public function test_admin_can_remove_image_without_uploading_a_new_one(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.products.store'), $this->productPayload([
            'image' => UploadedFile::fake()->image('foto.jpg', 800, 1000),
        ]));

        $product = Product::where('name', 'JEM Test Shirt')->firstOrFail();
        $oldPath = $product->image;

        $this->actingAs($admin)->put(route('admin.products.update', $product), array_merge(
            $this->productPayload(['category_id' => $product->category_id]),
            ['remove_image' => '1']
        ));

        $product->refresh();

        $this->assertNull($product->image);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_updating_does_not_delete_a_curated_catalog_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/jem-test-shirt.webp', 'contenido');

        $admin = $this->admin();
        $category = $this->createCategory();

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'JEM Test Shirt',
            'slug' => 'jem-test-shirt',
            'description' => 'x',
            'price' => 10000,
            'stock' => 5,
            'audience' => 'unisex',
            'image' => 'products/jem-test-shirt.webp',
            'active' => true,
        ]);

        $this->actingAs($admin)->put(route('admin.products.update', $product), array_merge(
            $this->productPayload(['category_id' => $category->id]),
            ['image' => UploadedFile::fake()->image('nueva.jpg', 800, 1000)]
        ));

        Storage::disk('public')->assertExists('products/jem-test-shirt.webp');
    }

    public function test_deleting_product_removes_its_uploaded_image(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.products.store'), $this->productPayload([
            'image' => UploadedFile::fake()->image('foto.jpg', 800, 1000),
        ]));

        $product = Product::where('name', 'JEM Test Shirt')->firstOrFail();
        $path = $product->image;

        $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_deleting_product_does_not_delete_a_curated_catalog_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/jem-test-shirt.webp', 'contenido');

        $admin = $this->admin();
        $category = $this->createCategory();

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'JEM Test Shirt',
            'slug' => 'jem-test-shirt',
            'description' => 'x',
            'price' => 10000,
            'stock' => 5,
            'audience' => 'unisex',
            'image' => 'products/jem-test-shirt.webp',
            'active' => true,
        ]);

        $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

        Storage::disk('public')->assertExists('products/jem-test-shirt.webp');
    }
}
