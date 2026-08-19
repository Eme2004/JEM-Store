@extends('layouts.app')

@section('content')
    <section class="checkout-page">
        <div class="container-fluid px-4 px-lg-5">

            <div class="cart-header">
                <p class="catalog-kicker mb-2">
                    Finalizar compra
                </p>

                <h1 class="catalog-title jem-editorial-title mb-2">
                    Checkout
                </h1>
            </div>


            @if (session('error'))
                <div class="jem-alert jem-alert-error mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="jem-alert jem-alert-error mb-4" role="alert">
                    Revisa los datos del formulario e intenta de nuevo.
                </div>
            @endif


            <form method="POST" action="{{ route('checkout.store') }}" data-checkout-form>
                @csrf

                <div class="row g-5">

                    {{-- Datos de envío y pago --}}
                    <div class="col-12 col-lg-7">

                        <div class="profile-edit-panel mb-4">

                            <div class="profile-edit-heading">
                                <p class="account-section-label mb-2">
                                    Envío
                                </p>

                                <h2 class="account-section-title mb-0">
                                    ¿A dónde lo enviamos?
                                </h2>
                            </div>

                            <div class="mb-4">
                                <label for="shipping_name" class="form-label auth-label">
                                    Nombre completo
                                </label>

                                <input id="shipping_name" type="text"
                                    class="form-control auth-input @error('shipping_name') is-invalid @enderror"
                                    name="shipping_name" value="{{ old('shipping_name', $user->name) }}" required>

                                @error('shipping_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="shipping_email" class="form-label auth-label">
                                    Correo electrónico
                                </label>

                                <input id="shipping_email" type="email"
                                    class="form-control auth-input @error('shipping_email') is-invalid @enderror"
                                    name="shipping_email" value="{{ old('shipping_email', $user->email) }}" required>

                                @error('shipping_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="shipping_phone" class="form-label auth-label">
                                    Teléfono
                                </label>

                                <input id="shipping_phone" type="text"
                                    class="form-control auth-input @error('shipping_phone') is-invalid @enderror"
                                    name="shipping_phone" value="{{ old('shipping_phone') }}" required>

                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="shipping_address" class="form-label auth-label">
                                    Dirección de envío
                                </label>

                                <textarea id="shipping_address" rows="3"
                                    class="form-control auth-input @error('shipping_address') is-invalid @enderror"
                                    name="shipping_address" required>{{ old('shipping_address') }}</textarea>

                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


                        <div class="profile-edit-panel">

                            <div class="profile-edit-heading">
                                <p class="account-section-label mb-2">
                                    Pago
                                </p>

                                <h2 class="account-section-title mb-0">
                                    Método de pago
                                </h2>
                            </div>

                            <div class="checkout-payment-options mb-4">

                                <label class="checkout-payment-option">
                                    <input type="radio" name="payment_method" value="card" data-payment-method
                                        {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }}>

                                    <span>
                                        <strong>Tarjeta</strong>
                                        <small>Modo de prueba, no se procesan cobros reales.</small>
                                    </span>
                                </label>

                                <label class="checkout-payment-option">
                                    <input type="radio" name="payment_method" value="paypal" data-payment-method
                                        {{ old('payment_method') === 'paypal' ? 'checked' : '' }}>

                                    <span>
                                        <strong>PayPal</strong>
                                        <small>Confirmación simulada.</small>
                                    </span>
                                </label>

                            </div>

                            @error('payment_method')
                                <div class="jem-alert jem-alert-error mb-4" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="checkout-card-fields" data-card-fields>

                                <div class="row g-4">

                                    <div class="col-12">
                                        <label for="card_holder" class="form-label auth-label">
                                            Nombre del titular
                                        </label>

                                        <input id="card_holder" type="text"
                                            class="form-control auth-input @error('card_holder') is-invalid @enderror"
                                            name="card_holder" value="{{ old('card_holder') }}"
                                            autocomplete="off">

                                        @error('card_holder')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="card_number" class="form-label auth-label">
                                            Número de tarjeta
                                        </label>

                                        <input id="card_number" type="text" inputmode="numeric"
                                            class="form-control auth-input @error('card_number') is-invalid @enderror"
                                            name="card_number" placeholder="4111111111111111" autocomplete="off">

                                        @error('card_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="card_expiry" class="form-label auth-label">
                                            Vencimiento
                                        </label>

                                        <input id="card_expiry" type="text"
                                            class="form-control auth-input @error('card_expiry') is-invalid @enderror"
                                            name="card_expiry" placeholder="MM/AA" autocomplete="off">

                                        @error('card_expiry')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="card_cvv" class="form-label auth-label">
                                            CVV
                                        </label>

                                        <input id="card_cvv" type="text" inputmode="numeric"
                                            class="form-control auth-input @error('card_cvv') is-invalid @enderror"
                                            name="card_cvv" placeholder="123" autocomplete="off">

                                        @error('card_cvv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <p class="product-detail-note mt-3 mb-0">
                                    Es una tarjeta de prueba: no se guarda ni se cobra ningún dato real.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Resumen del pedido --}}
                    <div class="col-12 col-lg-5">

                        <div class="cart-summary">

                            <h2 class="cart-summary-title">
                                Tu pedido
                            </h2>

                            <div class="checkout-summary-items">

                                @foreach ($items as $item)
                                    <div class="checkout-summary-item">
                                        <span>
                                            {{ $item['product']->name }}
                                            <small>x{{ $item['quantity'] }}</small>
                                        </span>

                                        <span>₡{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach

                            </div>

                            <div class="cart-summary-row">
                                <span>Subtotal</span>
                                <span>₡{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="cart-summary-row">
                                <span>IVA</span>
                                <span>₡{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <div class="cart-summary-row">
                                <span>Envío</span>
                                <span>
                                    @if ($shipping <= 0)
                                        Gratis
                                    @else
                                        ₡{{ number_format($shipping, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>

                            <div class="cart-summary-row cart-summary-total">
                                <span>Total</span>
                                <span>₡{{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <button type="submit" class="btn btn-dark cart-checkout-button">
                                Realizar pedido
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </section>
@endsection
