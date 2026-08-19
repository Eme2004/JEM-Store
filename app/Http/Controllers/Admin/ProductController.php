<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Carpeta donde se guardan las fotos subidas desde el panel admin.
     * Separada de storage/app/public/products/{slug}.ext (catálogo curado
     * versionado en Git) para que products:sync-images y los deploys nunca
     * la toquen ni sobrescriban lo que un administrador subió a mano.
     */
    private const UPLOAD_DIR = 'products/uploads';

    public function index()
    {
        $products = Product::with('category')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->categoryOptions();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['active'] = $request->boolean('active');
        unset($data['remove_image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store(self::UPLOAD_DIR, 'public');
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = $this->categoryOptions();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product);
        $removeImage = ! empty($data['remove_image']);
        unset($data['remove_image']);

        $data['active'] = $request->boolean('active');

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($product->image);
            $data['image'] = $request->file('image')->store(self::UPLOAD_DIR, 'public');
        } elseif ($removeImage) {
            $this->deleteUploadedImage($product->image);
            $data['image'] = null;
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $this->deleteUploadedImage($product->image);

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Producto eliminado.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'audience' => ['required', 'in:hombre,mujer,unisex'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Solo borra el archivo si vive bajo products/uploads/: nunca toca las
     * imágenes curadas de storage/app/public/products/{slug}.ext versionadas
     * en Git.
     */
    private function deleteUploadedImage(?string $path): void
    {
        if ($path && Str::startsWith($path, self::UPLOAD_DIR.'/')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . ++$suffix;
        }

        return $slug;
    }

    private function categoryOptions()
    {
        return Category::whereNotNull('parent_id')
            ->with('parent')
            ->orderBy('name')
            ->get();
    }
}
