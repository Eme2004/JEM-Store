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

            <div class="catalog-filters">

                <form method="GET" action="{{ route('products.index') }}" class="catalog-filter-form">

                    <div class="catalog-search">
                        <label for="search" class="catalog-filter-label">
                            Buscar
                        </label>

                        <input id="search" type="search" name="search" class="form-control catalog-filter-control"
                            placeholder="Buscar productos..." value="{{ request('search') }}">
                    </div>

                    <div>
                        <label for="audience" class="catalog-filter-label">
                            Público
                        </label>

                        <select id="audience" name="audience" class="form-select catalog-filter-control">
                            <option value="">Todos</option>

                            <option value="hombre" @selected(request('audience') === 'hombre')>
                                Hombre
                            </option>

                            <option value="mujer" @selected(request('audience') === 'mujer')>
                                Mujer
                            </option>

                            <option value="unisex" @selected(request('audience') === 'unisex')>
                                Unisex
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="category" class="catalog-filter-label">
                            Categoría
                        </label>

                        <select id="category" name="category" class="form-select catalog-filter-control">
                            <option value="">Todas</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="min_price" class="catalog-filter-label">
                            Precio mín.
                        </label>

                        <input id="min_price" type="number" name="min_price" class="form-control catalog-filter-control"
                            min="0" step="1" placeholder="₡0" value="{{ request('min_price') }}">
                    </div>

                    <div>
                        <label for="max_price" class="catalog-filter-label">
                            Precio máx.
                        </label>

                        <input id="max_price" type="number" name="max_price" class="form-control catalog-filter-control"
                            min="0" step="1" placeholder="₡100000" value="{{ request('max_price') }}">
                    </div>

                    <div class="catalog-filter-actions">

                        <button type="submit" class="btn btn-dark catalog-filter-button">
                            Aplicar filtros
                        </button>

                        <a href="{{ route('products.index') }}" class="catalog-clear-link">
                            Limpiar
                        </a>

                    </div>

                </form>
            </div>


            <div class="catalog-toolbar">

                <p class="catalog-count mb-0">
                    {{ $products->total() }}
                    {{ $products->total() === 1 ? 'producto' : 'productos' }}
                </p>

                <div class="catalog-quick-filters">

                    <a href="{{ route('products.index', ['new' => 1]) }}" class="catalog-quick-link">
                        Novedades
                    </a>

                    <a href="{{ route('products.index', ['sale' => 1]) }}" class="catalog-quick-link catalog-sale-link">
                        Ofertas
                    </a>

                </div>

            </div>

            <div class="row g-3 g-lg-4">

                @forelse ($products as $product)
                    <div class="col-6 col-md-4 col-xl-3">

                        <article class="product-card">

                            <a href="{{ route('products.show', $product) }}" class="product-card-image">
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
