<?php

namespace App\Providers;

use App\Models\Category;
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
        View::composer('layouts.app', function ($view) {
            $menMenuCategories = Category::whereNull('parent_id')
                ->with([
                    'children' => function ($query) {
                        $query
                            ->whereHas('products', function ($productQuery) {
                                $productQuery
                                    ->where('active', true)
                                    ->whereIn('audience', ['hombre', 'unisex']);
                            })
                            ->orderBy('name');
                    },
                ])
                ->get();

            $womenMenuCategories = Category::whereNull('parent_id')
                ->with([
                    'children' => function ($query) {
                        $query
                            ->whereHas('products', function ($productQuery) {
                                $productQuery
                                    ->where('active', true)
                                    ->whereIn('audience', ['mujer', 'unisex']);
                            })
                            ->orderBy('name');
                    },
                ])
                ->get();

            $shopMenuCategories = Category::whereIn('slug', [
                'calzado',
                'accesorios',
            ])
                ->with([
                    'children' => function ($query) {
                        $query
                            ->whereHas('products', function ($productQuery) {
                                $productQuery->where('active', true);
                            })
                            ->orderBy('name');
                    },
                ])
                ->get();

            $view->with([
                'menMenuCategories' => $menMenuCategories,
                'womenMenuCategories' => $womenMenuCategories,
                'shopMenuCategories' => $shopMenuCategories,
            ]);
        });
    }
}
