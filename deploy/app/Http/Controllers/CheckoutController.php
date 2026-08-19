<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout
    ) {
    }

    public function index()
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $user = Auth::user();

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $this->cart->subtotal($items),
            'tax' => $this->cart->tax($items),
            'shipping' => $this->cart->shipping($items),
            'total' => $this->cart->total($items),
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', Rule::in(['card', 'paypal'])],
            'card_holder' => ['required_if:payment_method,card', 'nullable', 'string', 'max:255'],
            'card_number' => ['required_if:payment_method,card', 'nullable', 'digits_between:13,19'],
            'card_expiry' => ['required_if:payment_method,card', 'nullable', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvv' => ['required_if:payment_method,card', 'nullable', 'digits_between:3,4'],
        ]);

        try {
            $order = $this->checkout->process(Auth::user(), $data);
        } catch (CheckoutException $e) {
            return redirect()
                ->route('cart.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('checkout.success', $order)
            ->with('status', 'Pedido realizado correctamente.');
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 404);

        $order->load('items.product');

        return view('checkout.success', compact('order'));
    }
}
