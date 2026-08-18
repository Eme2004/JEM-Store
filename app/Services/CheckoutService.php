<?php

namespace App\Services;

use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(private CartService $cart)
    {
    }

    /**
     * Procesa la compra dentro de una transacción: bloquea y revalida stock,
     * crea la orden y sus líneas, descuenta inventario y vacía el carrito.
     * Si algo falla, Laravel revierte automáticamente la transacción y no
     * queda ninguna orden ni descuento de stock a medias.
     */
    public function process(User $user, array $shipping): Order
    {
        $items = $this->linesToPurchase();

        return DB::transaction(function () use ($user, $shipping, $items) {
            foreach ($items as $item) {
                $product = Product::whereKey($item['product']->id)
                    ->lockForUpdate()
                    ->first();

                if (! $product || ! $product->active) {
                    throw new CheckoutException(
                        "El producto \"{$item['product']->name}\" ya no está disponible."
                    );
                }

                if ($product->stock < $item['quantity']) {
                    throw new CheckoutException(
                        "No hay suficiente stock de \"{$product->name}\". Revisa tu carrito."
                    );
                }

                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'tracking_number' => $this->generateTrackingNumber(),
                'subtotal' => $this->cart->subtotal($items),
                'tax' => $this->cart->tax($items),
                'shipping' => $this->cart->shipping($items),
                'total' => $this->cart->total($items),
                'payment_method' => $shipping['payment_method'],
                'payment_status' => 'paid',
                'status' => 'processing',
                'shipping_name' => $shipping['shipping_name'],
                'shipping_email' => $shipping['shipping_email'],
                'shipping_phone' => $shipping['shipping_phone'],
                'shipping_address' => $shipping['shipping_address'],
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->cart->clear();

            return $order;
        });
    }

    /**
     * Valida que el carrito tenga contenido y que el stock disponible
     * siga alcanzando para lo que el usuario pidió agregar. items() ya
     * recalcula precios desde la base de datos, así que aquí solo se
     * detecta si el stock bajó silenciosamente desde que se agregó.
     */
    private function linesToPurchase(): Collection
    {
        $requested = $this->cart->raw();
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            throw new CheckoutException('Tu carrito está vacío.');
        }

        foreach ($items as $item) {
            $requestedQuantity = $requested[$item['product']->id]['quantity'] ?? 0;

            if ($requestedQuantity > $item['quantity']) {
                throw new CheckoutException(
                    "El stock de \"{$item['product']->name}\" cambió. Revisa tu carrito antes de continuar."
                );
            }
        }

        return $items;
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'JEM-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function generateTrackingNumber(): string
    {
        do {
            $number = 'TRK-'.strtoupper(Str::random(8));
        } while (Order::where('tracking_number', $number)->exists());

        return $number;
    }
}
