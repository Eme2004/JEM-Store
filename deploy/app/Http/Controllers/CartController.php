<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index()
    {
        $items = $this->cart->items();

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $this->cart->subtotal($items),
            'tax' => $this->cart->tax($items),
            'shipping' => $this->cart->shipping($items),
            'total' => $this->cart->total($items),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        try {
            $this->cart->add($product, $data['quantity']);
        } catch (CartException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('cart.index')
            ->with('status', 'Producto agregado al carrito.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cart->update($product, $data['quantity']);
        } catch (CartException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('cart.index')
            ->with('status', 'Carrito actualizado.');
    }

    public function destroy(Product $product)
    {
        $this->cart->remove($product);

        return redirect()
            ->route('cart.index')
            ->with('status', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        $this->cart->clear();

        return redirect()
            ->route('cart.index')
            ->with('status', 'Carrito vaciado.');
    }
}
