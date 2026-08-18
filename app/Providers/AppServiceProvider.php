<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.app', function ($view) {
            $menMenuCategories = Category::whereNull('parent_id')
                ->with([
                    'children' => function ($query) {
                        $query->whereHas('products', function ($productQuery) {
                            $productQuery
                                ->where('active', true)
                                ->whereIn('audience', ['hombre', 'unisex']);
                        });
                    },
                ])
                ->get();

            $womenMenuCategories = Category::whereNull('parent_id')
                ->with([
                    'children' => function ($query) {
                        $query->whereHas('products', function ($productQuery) {
                            $productQuery
                                ->where('active', true)
                                ->whereIn('audience', ['mujer', 'unisex']);
                        });
                    },
                ])
                ->get();

            $shopMenuCategories = Category::whereNull('parent_id')
                ->whereIn('slug', ['calzado', 'accesorios'])
                ->with('children')
                ->get();

            $view->with(compact(
                'menMenuCategories',
                'womenMenuCategories',
                'shopMenuCategories'
            ));
        });
    }
}
