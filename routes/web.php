<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

});