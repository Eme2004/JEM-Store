<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use App\Services\Payments\BraintreeGatewayService;
use App\Services\Payments\FakeSandboxGatewayService;
use App\Services\Payments\PaymentGatewayContract;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayContract::class, function () {
            $config = config('services.braintree');

            // Sin credenciales configuradas todavía: se usa una pasarela
            // simulada localmente equivalente para que el checkout y sus
            // tests funcionen igual. En cuanto BRAINTREE_PRIVATE_KEY tenga
            // un valor real (sandbox), esto cambia automáticamente al SDK
            // real de Braintree sin tocar ningún otro código.
            if (empty($config['merchant_id']) || empty($config['public_key']) || empty($config['private_key'])) {
                return new FakeSandboxGatewayService();
            }

            return new BraintreeGatewayService($config);
        });
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

            $cartCount = app(CartService::class)->count();

            $view->with(compact(
                'menMenuCategories',
                'womenMenuCategories',
                'shopMenuCategories',
                'cartCount'
            ));
        });
    }
}
