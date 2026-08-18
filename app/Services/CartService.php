<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Contenido crudo de la sesión (product_id + cantidad solicitada),
     * sin reconciliar contra el stock actual. Lo usa CheckoutService para
     * detectar si el stock cambió desde que se agregó al carrito.
     */
    public function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Recupera las líneas del carrito reconstruyendo precio y stock
     * desde la base de datos. Nunca se confía en lo guardado en sesión
     * salvo el product_id y la cantidad solicitada.
     */
    public function items(): Collection
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if (empty($cart)) {
            return collect();
        }

        $products = Product::with('category')
            ->whereIn('id', array_keys($cart))
            ->where('active', true)
            ->get()
            ->keyBy('id');

        $cleaned = [];
        $items = collect();

        foreach ($cart as $productId => $row) {
            $product = $products->get($productId);

            if (! $product || $product->stock < 1) {
                continue;
            }

            $quantity = min((int) $row['quantity'], $product->stock);

            if ($quantity < 1) {
                continue;
            }

            $unitPrice = (float) ($product->sale_price ?? $product->price);

            $cleaned[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];

            $items->push([
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => round($unitPrice * $quantity, 2),
            ]);
        }

        Session::put(self::SESSION_KEY, $cleaned);

        return $items;
    }

    public function add(Product $product, int $quantity): void
    {
        if (! $product->active) {
            throw new CartException('Este producto no está disponible.');
        }

        if ($quantity < 1) {
            throw new CartException('La cantidad debe ser mayor a cero.');
        }

        if ($product->stock < 1) {
            throw new CartException('Este producto está agotado.');
        }

        $cart = Session::get(self::SESSION_KEY, []);

        $currentQuantity = $cart[$product->id]['quantity'] ?? 0;
        $newQuantity = $currentQuantity + $quantity;

        if ($newQuantity > $product->stock) {
            throw new CartException('La cantidad solicitada supera las existencias disponibles.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $newQuantity,
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(Product $product, int $quantity): void
    {
        if (! $product->active) {
            throw new CartException('Este producto no está disponible.');
        }

        if ($quantity < 1) {
            throw new CartException('La cantidad debe ser mayor a cero.');
        }

        if ($quantity > $product->stock) {
            throw new CartException('La cantidad solicitada supera las existencias disponibles.');
        }

        $cart = Session::get(self::SESSION_KEY, []);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $quantity,
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(Product $product): void
    {
        $cart = Session::get(self::SESSION_KEY, []);

        unset($cart[$product->id]);

        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function subtotal(?Collection $items = null): float
    {
        $items ??= $this->items();

        return round((float) $items->sum('subtotal'), 2);
    }

    public function tax(?Collection $items = null): float
    {
        return round($this->subtotal($items) * (float) config('store.tax_rate'), 2);
    }

    public function shipping(?Collection $items = null): float
    {
        $subtotal = $this->subtotal($items);

        if ($subtotal <= 0) {
            return 0.0;
        }

        return $subtotal >= (float) config('store.free_shipping_from')
            ? 0.0
            : (float) config('store.shipping_cost');
    }

    public function total(?Collection $items = null): float
    {
        $items ??= $this->items();

        return round(
            $this->subtotal($items) + $this->tax($items) + $this->shipping($items),
            2
        );
    }
}
