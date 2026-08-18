<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// --------------------------------------------------
// Rutas públicas
// --------------------------------------------------

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/productos', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/productos/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');


// --------------------------------------------------
// Carrito de compras
// --------------------------------------------------

Route::get('/carrito', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/carrito', [CartController::class, 'store'])
    ->name('cart.store');

Route::patch('/carrito/{product}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/carrito/{product}', [CartController::class, 'destroy'])
    ->name('cart.destroy');

Route::delete('/carrito', [CartController::class, 'clear'])
    ->name('cart.clear');


// --------------------------------------------------
// Autenticación
// --------------------------------------------------

Auth::routes();


// --------------------------------------------------
// Rutas protegidas
// --------------------------------------------------

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    // --------------------------------------------------
    // Checkout
    // --------------------------------------------------

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/checkout/{order}/confirmacion', [CheckoutController::class, 'success'])
        ->name('checkout.success');


    // --------------------------------------------------
    // Historial de pedidos
    // --------------------------------------------------

    Route::get('/pedidos', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/pedidos/{order}', [OrderController::class, 'show'])
        ->name('orders.show');


    // --------------------------------------------------
    // Reportes de ventas
    // --------------------------------------------------

    Route::get('/reportes', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/reportes/pdf', [ReportController::class, 'pdf'])
        ->name('reports.pdf');

});