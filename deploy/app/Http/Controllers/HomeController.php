<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $newProducts = Product::with('category')
            ->where('active', true)
            ->latest()
            ->take(4)
            ->get();

        $saleProducts = Product::with('category')
            ->where('active', true)
            ->whereNotNull('sale_price')
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact(
            'newProducts',
            'saleProducts'
        ));
    }
}
