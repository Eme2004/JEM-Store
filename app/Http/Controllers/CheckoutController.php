<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\Payments\PaymentGatewayContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout,
        private PaymentGatewayContract $gateway
    ) {
    }

    public function index(Request $request)
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $user = Auth::user();

        // Token de un solo uso: evita que un doble clic en "Realizar pedido"
        // (o un reenvío del mismo formulario) genere dos pedidos. Se
        // consume únicamente cuando el pedido se crea con éxito.
        $checkoutToken = Str::random(40);
        $request->session()->put('checkout.token', $checkoutToken);

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $this->cart->subtotal($items),
            'tax' => $this->cart->tax($items),
            'shipping' => $this->cart->shipping($items),
            'total' => $this->cart->total($items),
            'user' => $user,
            'checkoutToken' => $checkoutToken,
            'braintreeClientToken' => $this->gateway->clientToken(),
            'braintreeConfigured' => filled(config('services.braintree.private_key')),
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
            'payment_method_nonce' => ['required_if:payment_method,card', 'nullable', 'string'],
            'checkout_token' => ['required', 'string'],
        ]);

        // Se usa raw() (lectura directa de sesión) y no items(): items()
        // reconcilia y sobrescribe la cantidad contra el stock actual como
        // efecto secundario, lo que le ganaría de mano a la detección de
        // "el stock cambió" que hace CheckoutService::linesToPurchase().
        if (empty($this->cart->raw())) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $sessionToken = $request->session()->get('checkout.token');

        if (! $sessionToken || $data['checkout_token'] !== $sessionToken) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Este pedido ya fue procesado o el formulario expiró. Intenta de nuevo.');
        }

        try {
            $order = $this->checkout->process(Auth::user(), $data);
        } catch (CheckoutException $e) {
            return redirect()
                ->route('checkout.index')
                ->withInput()
                ->with('error', $e->getMessage());
        }

        // Solo se invalida el token cuando el pedido se creó con éxito, así
        // un rechazo de pago permite reintentar sin recargar la página.
        $request->session()->forget('checkout.token');

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
