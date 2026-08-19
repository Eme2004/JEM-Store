{{-- Requiere $order con items.product cargado. --}}

<div class="checkout-success-numbers">

    <div>
        <span class="account-detail-label">Número de pedido</span>
        <span class="checkout-success-number">{{ $order->order_number }}</span>
    </div>

    <div>
        <span class="account-detail-label">Número de seguimiento</span>
        <span class="checkout-success-number">{{ $order->tracking_number }}</span>
    </div>

</div>


<div class="account-details">

    <div class="account-detail">
        <span class="account-detail-label">Método de pago</span>
        <span class="account-detail-value">
            {{ $order->payment_method_label }} ({{ $order->payment_status_label }})
            @if ($order->card_display)
                · {{ $order->card_display }}
            @endif
        </span>
    </div>

    <div class="account-detail">
        <span class="account-detail-label">Estado del pedido</span>
        <span class="account-detail-value">
            {{ $order->status_label }}
        </span>
    </div>

    <div class="account-detail">
        <span class="account-detail-label">Enviado a</span>
        <span class="account-detail-value">
            {{ $order->shipping_name }} · {{ $order->shipping_phone }}<br>
            {{ $order->shipping_address }}
        </span>
    </div>

</div>


@if (str_starts_with((string) $order->payment_gateway, 'braintree'))
    <div class="payment-gateway-panel">

        <h3 class="payment-gateway-panel__title">
            Detalle de la transacción
        </h3>

        <div class="account-details">

            <div class="account-detail">
                <span class="account-detail-label">Pasarela</span>
                <span class="account-detail-value">Braintree</span>
            </div>

            <div class="account-detail">
                <span class="account-detail-label">Entorno</span>
                <span class="account-detail-value">{{ strtoupper($order->payment_environment) }}</span>
            </div>

            <div class="account-detail">
                <span class="account-detail-label">Estado</span>
                <span class="account-detail-value">{{ strtoupper($order->payment_status_label) }}</span>
            </div>

            @if ($order->transaction_id)
                <div class="account-detail">
                    <span class="account-detail-label">Transaction ID</span>
                    <span class="account-detail-value">{{ $order->transaction_id }}</span>
                </div>
            @endif

        </div>

        <p class="payment-gateway-panel__notice">
            ⚠ Transacción de prueba — No se procesó dinero real
        </p>

    </div>
@endif


<div class="checkout-success-items">

    @foreach ($order->items as $item)
        <div class="checkout-summary-item">
            <span>
                {{ $item->product_name }}
                <small>x{{ $item->quantity }}</small>
            </span>

            <span>₡{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    @endforeach

</div>

<div class="cart-summary-row">
    <span>Subtotal</span>
    <span>₡{{ number_format($order->subtotal, 0, ',', '.') }}</span>
</div>

<div class="cart-summary-row">
    <span>IVA</span>
    <span>₡{{ number_format($order->tax, 0, ',', '.') }}</span>
</div>

<div class="cart-summary-row">
    <span>Envío</span>
    <span>
        @if ($order->shipping <= 0)
            Gratis
        @else
            ₡{{ number_format($order->shipping, 0, ',', '.') }}
        @endif
    </span>
</div>

<div class="cart-summary-row cart-summary-total">
    <span>Total</span>
    <span>₡{{ number_format($order->total, 0, ',', '.') }}</span>
</div>
