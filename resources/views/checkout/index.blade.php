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

                <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                <input type="hidden" name="payment_method_nonce" data-payment-method-nonce value="">
                <div class="jem-alert jem-alert-error mb-4 d-none" role="alert" data-braintree-error></div>

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

                                <label class="checkout-payment-option {{ $braintreeConfigured ? '' : 'checkout-payment-option-disabled' }}">
                                    <input type="radio" name="payment_method" value="card" data-payment-method
                                        {{ ! $braintreeConfigured ? 'disabled' : '' }}
                                        {{ $braintreeConfigured && old('payment_method', 'card') === 'card' ? 'checked' : '' }}>

                                    <span>
                                        <strong>Tarjeta</strong>
                                        <small>
                                            @if ($braintreeConfigured)
                                                Braintree Sandbox, no se procesan cobros reales.
                                            @else
                                                Pendiente de credenciales sandbox.
                                            @endif
                                        </small>
                                    </span>
                                </label>

                                <label class="checkout-payment-option">
                                    <input type="radio" name="payment_method" value="paypal" data-payment-method
                                        {{ old('payment_method', $braintreeConfigured ? '' : 'paypal') === 'paypal' ? 'checked' : '' }}>

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

                                <span class="checkout-sandbox-badge">
                                    ⚠ Braintree Sandbox — no se procesa dinero real
                                </span>

                                @if ($braintreeConfigured)
                                    <div class="row g-4">

                                        <div class="col-12">
                                            <label for="card_holder" class="form-label auth-label">
                                                Nombre del titular
                                            </label>

                                            <input id="card_holder" type="text"
                                                class="form-control auth-input"
                                                value="{{ old('card_holder') }}" autocomplete="off">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label auth-label">
                                                Número de tarjeta
                                            </label>

                                            {{-- Campo hospedado por Braintree: JEM Store nunca ve ni
                                                 recibe el número real de la tarjeta. --}}
                                            <div id="bt-card-number" class="form-control auth-input braintree-hosted-field"></div>
                                        </div>

                                        <div class="col-6">
                                            <label class="form-label auth-label">
                                                Vencimiento
                                            </label>

                                            <div id="bt-expiration-date" class="form-control auth-input braintree-hosted-field"></div>
                                        </div>

                                        <div class="col-6">
                                            <label class="form-label auth-label">
                                                CVV
                                            </label>

                                            {{-- Campo hospedado por Braintree: el CVV tampoco llega
                                                 nunca al servidor de JEM Store. --}}
                                            <div id="bt-cvv" class="form-control auth-input braintree-hosted-field"></div>
                                        </div>

                                    </div>

                                    <p class="product-detail-note mt-3 mb-0">
                                        Tarjeta de prueba (sandbox): por ejemplo 4111 1111 1111 1111,
                                        cualquier fecha futura y cualquier CVV de 3 dígitos. No se guarda
                                        ni se cobra ningún dato real.
                                    </p>
                                @else
                                    <div class="jem-alert mb-0" role="status">
                                        El pago con tarjeta todavía no tiene credenciales de Braintree
                                        Sandbox configuradas en este entorno, así que el formulario de
                                        tarjeta está desactivado por ahora. Podés probar el flujo completo
                                        de compra con la opción <strong>PayPal</strong> (confirmación
                                        simulada) mientras tanto.
                                    </div>
                                @endif

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

                            <button type="submit" class="btn btn-dark cart-checkout-button" data-checkout-submit>
                                Realizar pedido
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </section>

    @push('scripts')
        <script>
            // Guard básico anti doble-envío: se aplica siempre, incluso si
            // Braintree todavía no está configurado (flujo de PayPal).
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('[data-checkout-form]');
                const submitButton = document.querySelector('[data-checkout-submit]');

                if (!form || !submitButton || {{ $braintreeConfigured ? 'true' : 'false' }}) {
                    return;
                }

                form.addEventListener('submit', function () {
                    submitButton.disabled = true;
                });
            });
        </script>
    @endpush

    @if ($braintreeConfigured)
        @push('scripts')
            <script src="https://js.braintreegateway.com/web/3.106.0/js/client.min.js"></script>
            <script src="https://js.braintreegateway.com/web/3.106.0/js/hosted-fields.min.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const form = document.querySelector('[data-checkout-form]');
                    const submitButton = document.querySelector('[data-checkout-submit]');
                    const nonceInput = document.querySelector('[data-payment-method-nonce]');
                    const errorBox = document.querySelector('[data-braintree-error]');

                    if (!form || !submitButton) {
                        return;
                    }

                    const showError = (message) => {
                        if (!errorBox) {
                            return;
                        }
                        errorBox.textContent = message;
                        errorBox.classList.remove('d-none');
                    };

                    const hideError = () => {
                        errorBox?.classList.add('d-none');
                    };

                    const isCardSelected = () => {
                        const selected = document.querySelector('[data-payment-method]:checked');
                        return selected?.value === 'card';
                    };

                    let hostedFieldsInstance = null;

                    braintree.client.create({
                        authorization: '{{ $braintreeClientToken }}',
                    }, function (clientErr, clientInstance) {
                        if (clientErr) {
                            showError('No se pudo inicializar el pago. Recarga la página e intenta de nuevo.');
                            return;
                        }

                        braintree.hostedFields.create({
                            client: clientInstance,
                            styles: {
                                input: {
                                    'font-size': '0.95rem',
                                    color: '#161616',
                                },
                            },
                            fields: {
                                number: {
                                    selector: '#bt-card-number',
                                    placeholder: '4111 1111 1111 1111',
                                },
                                expirationDate: {
                                    selector: '#bt-expiration-date',
                                    placeholder: 'MM/YY',
                                },
                                cvv: {
                                    selector: '#bt-cvv',
                                    placeholder: '123',
                                },
                            },
                        }, function (hostedFieldsErr, instance) {
                            if (hostedFieldsErr) {
                                showError('No se pudieron cargar los campos de tarjeta. Recarga la página.');
                                return;
                            }

                            hostedFieldsInstance = instance;

                            instance.on('focus', function (event) {
                                document.getElementById(event.emittedBy === 'number' ? 'bt-card-number'
                                    : event.emittedBy === 'expirationDate' ? 'bt-expiration-date' : 'bt-cvv')
                                    ?.classList.add('braintree-hosted-fields-focused');
                            });

                            instance.on('blur', function (event) {
                                document.getElementById(event.emittedBy === 'number' ? 'bt-card-number'
                                    : event.emittedBy === 'expirationDate' ? 'bt-expiration-date' : 'bt-cvv')
                                    ?.classList.remove('braintree-hosted-fields-focused');
                            });
                        });
                    });

                    form.addEventListener('submit', function (event) {
                        if (!isCardSelected()) {
                            // PayPal: sigue el envío normal simulado, sin tokenizar tarjeta.
                            submitButton.disabled = true;
                            return;
                        }

                        event.preventDefault();
                        hideError();

                        if (!hostedFieldsInstance) {
                            showError('Los campos de pago todavía se están cargando. Espera un momento e intenta de nuevo.');
                            return;
                        }

                        submitButton.disabled = true;

                        hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                            if (tokenizeErr) {
                                submitButton.disabled = false;
                                showError('Revisa los datos de la tarjeta: ' + (tokenizeErr.message || 'son inválidos.'));
                                return;
                            }

                            nonceInput.value = payload.nonce;
                            form.submit();
                        });
                    });
                });
            </script>
        @endpush
    @endif
@endsection
