@extends('layouts.app')

@section('content')
    <section class="cart-page">
        <div class="container-fluid px-4 px-lg-5">

            <div class="cart-header">
                <p class="catalog-kicker mb-2">
                    Tu compra
                </p>

                <h1 class="catalog-title jem-editorial-title mb-2">
                    Carrito
                </h1>
            </div>


            @if (session('status'))
                <div class="jem-alert mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="jem-alert jem-alert-error mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif


            @if ($items->isEmpty())

                <div class="cart-empty">
                    <h2 class="jem-editorial-title mb-3">
                        Tu carrito está vacío
                    </h2>

                    <p class="mb-4">
                        Explora la colección y encuentra tu próxima pieza JEM.
                    </p>

                    <a href="{{ route('products.index') }}" class="btn btn-dark cart-empty-button">
                        Explorar colección
                    </a>
                </div>

            @else

                <div class="row g-5">

                    {{-- Líneas del carrito --}}
                    <div class="col-12 col-lg-8">

                        <div class="cart-items">

                            @foreach ($items as $item)
                                @php $product = $item['product']; @endphp

                                <article class="cart-item">

                                    <a href="{{ route('products.show', $product) }}" class="cart-item-image">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="product-placeholder">
                                                <span>JEM</span>
                                            </div>
                                        @endif
                                    </a>


                                    <div class="cart-item-body">

                                        <div class="cart-item-info">

                                            <p class="cart-item-category">
                                                {{ $product->category->name }}
                                            </p>

                                            <a href="{{ route('products.show', $product) }}" class="cart-item-name">
                                                {{ $product->name }}
                                            </a>

                                            <div class="cart-item-price">
                                                @if ($product->sale_price)
                                                    <span class="product-price-old">
                                                        ₡{{ number_format($product->price, 0, ',', '.') }}
                                                    </span>

                                                    <span class="product-price-sale">
                                                        ₡{{ number_format($item['unit_price'], 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span>
                                                        ₡{{ number_format($item['unit_price'], 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="cart-item-stock">
                                                {{ $product->stock }} disponibles
                                            </p>

                                        </div>


                                        <form action="{{ route('cart.update', $product) }}" method="POST"
                                            class="cart-item-quantity">
                                            @csrf
                                            @method('PATCH')

                                            <label for="quantity-{{ $product->id }}" class="visually-hidden">
                                                Cantidad
                                            </label>

                                            <input type="number" id="quantity-{{ $product->id }}" name="quantity"
                                                min="1" max="{{ $product->stock }}" value="{{ $item['quantity'] }}"
                                                class="cart-item-quantity-input">

                                            <button type="submit" class="cart-item-quantity-button">
                                                Actualizar
                                            </button>
                                        </form>


                                        <div class="cart-item-subtotal">
                                            ₡{{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </div>


                                        <form action="{{ route('cart.destroy', $product) }}" method="POST"
                                            class="cart-item-remove">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="cart-item-remove-button"
                                                aria-label="Eliminar {{ $product->name }}">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>

                                </article>
                            @endforeach

                        </div>


                        <div class="cart-actions">
                            <a href="{{ route('products.index') }}" class="jem-outline-button">
                                Continuar comprando
                            </a>

                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="cart-clear-link">
                                    Vaciar carrito
                                </button>
                            </form>
                        </div>

                    </div>


                    {{-- Resumen --}}
                    <div class="col-12 col-lg-4">

                        <div class="cart-summary">

                            <h2 class="cart-summary-title">
                                Resumen
                            </h2>

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

                            @auth
                                <a href="{{ route('checkout.index') }}" class="btn btn-dark cart-checkout-button">
                                    Continuar al pago
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-dark cart-checkout-button">
                                    Inicia sesión para continuar
                                </a>
                            @endauth

                        </div>

                    </div>

                </div>

            @endif

        </div>
    </section>
@endsection
