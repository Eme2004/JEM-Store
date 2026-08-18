@extends('layouts.app')

@section('content')
    <section class="product-detail-page">
        <div class="container-fluid px-4 px-lg-5">

            <div class="product-detail-back">
                <a href="{{ route('products.index') }}">
                    ← Volver a la colección
                </a>
            </div>


            {{-- Producto principal --}}
            <div class="row g-5 align-items-start">

                {{-- Imagen --}}
                <div class="col-12 col-lg-7">
                    <div class="product-detail-image">

                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div class="product-detail-placeholder">
                                <span>JEM</span>
                            </div>
                        @endif

                        @if ($product->sale_price)
                            <span class="product-badge">
                                Oferta
                            </span>
                        @endif

                    </div>
                </div>


                {{-- Información --}}
                <div class="col-12 col-lg-5">
                    <div class="product-detail-info">

                        <p class="product-detail-category">
                            {{ $product->category->parent?->name }}

                            @if ($product->category->parent)
                                /
                            @endif

                            {{ $product->category->name }}
                        </p>


                        <h1 class="product-detail-title jem-editorial-title">
                            {{ $product->name }}
                        </h1>


                        <div class="product-detail-price">

                            @if ($product->sale_price)
                                <span class="product-detail-price-old">
                                    ₡{{ number_format($product->price, 0, ',', '.') }}
                                </span>

                                <span class="product-detail-price-sale">
                                    ₡{{ number_format($product->sale_price, 0, ',', '.') }}
                                </span>
                            @else
                                <span>
                                    ₡{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @endif

                        </div>


                        <div class="product-detail-divider"></div>


                        <p class="product-detail-description">
                            {{ $product->description }}
                        </p>


                        <div class="product-detail-meta">

                            <div>
                                <span class="product-detail-meta-label">
                                    Público
                                </span>

                                <span class="product-detail-meta-value">
                                    {{ ucfirst($product->audience) }}
                                </span>
                            </div>


                            <div>
                                <span class="product-detail-meta-label">
                                    Disponibilidad
                                </span>

                                <span class="product-detail-meta-value">
                                    @if ($product->stock > 0)
                                        En stock
                                    @else
                                        Agotado
                                    @endif
                                </span>
                            </div>

                        </div>


                        <div class="product-detail-divider"></div>


                        @if (session('error'))
                            <div class="jem-alert jem-alert-error mb-3" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($product->stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="product-detail-form">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="product-detail-quantity">
                                    <label for="quantity" class="product-detail-meta-label">
                                        Cantidad
                                    </label>

                                    <input type="number" id="quantity" name="quantity" min="1"
                                        max="{{ $product->stock }}" value="1" class="product-detail-quantity-input">
                                </div>

                                <button type="submit" class="btn btn-dark product-detail-button">
                                    Agregar al carrito
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-dark product-detail-button" disabled>
                                Producto agotado
                            </button>
                        @endif

                    </div>
                </div>

            </div>


            {{-- Vistos recientemente --}}
            @if ($recentProducts->isNotEmpty())
                <section class="recent-products">

                    <div class="recent-products-header">

                        <p class="catalog-kicker mb-2">
                            Tu selección
                        </p>

                        <h2 class="recent-products-title jem-editorial-title">
                            Vistos recientemente
                        </h2>

                    </div>


                    <div class="row g-3 g-lg-4">

                        @foreach ($recentProducts as $recentProduct)
                            <div class="col-6 col-md-4 col-lg-3">

                                <article class="product-card">

                                    <a href="{{ route('products.show', $recentProduct) }}" class="product-card-image">

                                        @if ($recentProduct->image)
                                            <img src="{{ asset('storage/' . $recentProduct->image) }}"
                                                alt="{{ $recentProduct->name }}">
                                        @else
                                            <div class="product-placeholder">
                                                <span>JEM</span>
                                            </div>
                                        @endif


                                        @if ($recentProduct->sale_price)
                                            <span class="product-badge">
                                                Oferta
                                            </span>
                                        @endif

                                    </a>


                                    <div class="product-card-body">

                                        <p class="product-category mb-1">
                                            {{ $recentProduct->category->name }}
                                        </p>

                                        <h3 class="product-name">
                                            {{ $recentProduct->name }}
                                        </h3>


                                        <div class="product-price">

                                            @if ($recentProduct->sale_price)
                                                <span class="product-price-old">
                                                    ₡{{ number_format($recentProduct->price, 0, ',', '.') }}
                                                </span>

                                                <span class="product-price-sale">
                                                    ₡{{ number_format($recentProduct->sale_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span>
                                                    ₡{{ number_format($recentProduct->price, 0, ',', '.') }}
                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </article>

                            </div>
                        @endforeach

                    </div>

                </section>
            @endif

        </div>
    </section>
@endsection
