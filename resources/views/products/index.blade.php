@extends('layouts.app')

@section('content')
    <section class="catalog-page">
        <div class="container-fluid px-4 px-lg-5">

            <div class="catalog-header">
                <p class="catalog-kicker mb-2">
                    JEM Collection
                </p>

                <h1 class="catalog-title jem-editorial-title mb-2">
                    Nuestra colección
                </h1>

                <p class="catalog-subtitle mb-0">
                    Descubre una selección de prendas, calzado y accesorios diseñados para un estilo contemporáneo.
                </p>
            </div>

            <div class="catalog-toolbar">
                <p class="catalog-count mb-0">
                    {{ $products->total() }}
                    {{ $products->total() === 1 ? 'producto' : 'productos' }}
                </p>
            </div>

            <div class="row g-3 g-lg-4">

                @forelse ($products as $product)
                    <div class="col-6 col-md-4 col-xl-3">

                        <article class="product-card">

                            <a href="#" class="product-card-image">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="product-placeholder">
                                        <span>JEM</span>
                                    </div>
                                @endif

                                @if ($product->sale_price)
                                    <span class="product-badge">
                                        Oferta
                                    </span>
                                @endif
                            </a>

                            <div class="product-card-body">

                                <p class="product-category mb-1">
                                    {{ $product->category->name }}
                                </p>

                                <h2 class="product-name">
                                    {{ $product->name }}
                                </h2>

                                <div class="product-price">
                                    @if ($product->sale_price)
                                        <span class="product-price-old">
                                            ₡{{ number_format($product->price, 0, ',', '.') }}
                                        </span>

                                        <span class="product-price-sale">
                                            ₡{{ number_format($product->sale_price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span>
                                            ₡{{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                            </div>

                        </article>

                    </div>
                @empty

                    <div class="col-12">
                        <div class="catalog-empty">
                            <h2>No encontramos productos</h2>

                            <p class="mb-0">
                                La colección estará disponible próximamente.
                            </p>
                        </div>
                    </div>
                @endforelse

            </div>

            @if ($products->hasPages())
                <div class="catalog-pagination">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </section>
@endsection
