<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->where('active', true);

        // Búsqueda por nombre o descripción
        if ($request->filled('search')) {
            $search = $request->input('search');

            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Público: Hombre / Mujer
        if ($request->filled('audience')) {
            $audience = $request->input('audience');

            if (in_array($audience, ['hombre', 'mujer'])) {
                $products->whereIn('audience', [
                    $audience,
                    'unisex',
                ]);
            }

            if ($audience === 'unisex') {
                $products->where('audience', 'unisex');
            }
        }

        // Subcategoría específica
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');

            $products->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        // Grupo: ropa / calzado / accesorios
        if ($request->filled('group')) {
            $groupSlug = $request->input('group');

            $products->whereHas('category.parent', function ($query) use ($groupSlug) {
                $query->where('slug', $groupSlug);
            });
        }

        // Productos en oferta
        if ($request->boolean('sale')) {
            $products->whereNotNull('sale_price');
        }

        // Novedades
        if ($request->boolean('new')) {
            $products->latest();
        } else {
            $products->orderBy('name');
        }

        $products = $products
            ->paginate(12)
            ->withQueryString();

        $categories = Category::whereNotNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('products.index', compact(
            'products',
            'categories'
        ));
    }

    public function show(Product $product)
    {
        abort_unless($product->active, 404);

        $product->load('category.parent');

        $cookieValue = Cookie::get('jem_recent_products', '');

        $recentIds = $cookieValue !== ''
            ? array_map('intval', explode(',', $cookieValue))
            : [];

        $recentIds = array_values(
            array_filter(
                $recentIds,
                fn($id) => $id !== $product->id
            )
        );

        $recentProducts = Product::with('category')
            ->where('active', true)
            ->whereIn('id', array_slice($recentIds, 0, 4))
            ->get()
            ->sortBy(
                fn($item) => array_search($item->id, $recentIds)
            )
            ->values();

        array_unshift($recentIds, $product->id);

        $recentIds = array_slice(
            array_values(array_unique($recentIds)),
            0,
            5
        );

        Cookie::queue(
            'jem_recent_products',
            implode(',', $recentIds),
            60 * 24 * 30
        );

        return view('products.show', compact(
            'product',
            'recentProducts'
        ));
    }
}
