@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="jem-home-hero">
        <div class="container">
            <div class="row align-items-center jem-home-hero__row">
                <div class="col-12 col-lg-7">
                    <p class="jem-home-kicker mb-3">
                        Nueva colección / 2026
                    </p>

                    <h1 class="jem-home-hero__title jem-editorial-title">
                        Nothing is
                        casual.
                    </h1>

                    <p class="jem-home-hero__description">
                        Prendas esenciales y accesorios atemporales,
                        diseñados con intención en cada detalle.
                    </p>

                    <div class="jem-home-actions">
                        <a href="{{ route('products.index') }}" class="btn btn-dark jem-home-button">
                            Ver colección
                        </a>

                        <a href="{{ route('products.index', ['new' => 1]) }}" class="btn jem-outline-button">
                            Novedades
                        </a>
                    </div>
                </div>

                <div class="col-12 col-lg-5 mt-5 mt-lg-0">
                    <div class="jem-home-hero__visual">
                        <span class="jem-home-hero__visual-label">
                            JEM
                        </span>

                        <div class="jem-home-hero__visual-copy">
                            <span>FORM</span>
                            <span>MOTION</span>
                            <span>IDENTITY</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Novedades --}}
    @if ($newProducts->isNotEmpty())
        <section class="jem-home-section">
            <div class="container">
                <div class="jem-home-heading">
                    <div>
                        <p class="jem-home-kicker mb-2">
                            Recién llegados
                        </p>

                        <h2 class="jem-home-section-title jem-editorial-title mb-0">
                            Lo nuevo en JEM
                        </h2>
                    </div>

                    <a href="{{ route('products.index', ['new' => 1]) }}" class="jem-home-link">
                        Ver novedades
                    </a>
                </div>

                <div class="row g-4">
                    @foreach ($newProducts as $product)
                        <div class="col-6 col-lg-3">
                            <article class="product-card">
                                <a href="{{ route('products.show', $product) }}" class="product-card-image">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            loading="lazy">
                                    @else
                                        <div class="product-placeholder">
                                            <span>JEM</span>
                                        </div>
                                    @endif
                                </a>

                                <div class="product-card-body">
                                    <p class="product-category mb-2">
                                        {{ $product->category->name }}
                                    </p>

                                    <h3 class="product-name">
                                        <a href="{{ route('products.show', $product) }}"
                                            class="text-decoration-none text-reset">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

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
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- Colecciones --}}
    <section id="coleccion" class="jem-home-section jem-home-section--stone">
        <div class="container">
            <div class="jem-home-heading">
                <div>
                    <p class="jem-home-kicker mb-2">
                        Explora
                    </p>

                    <h2 class="jem-home-section-title jem-editorial-title mb-0">
                        Explora tu identidad
                    </h2>
                </div>
            </div>

            <div class="row g-4">
                {{-- Tailored Motion --}}
                <div class="col-12 col-md-4">
                    <a href="{{ route('products.index', ['audience' => 'hombre']) }}" class="jem-home-collection-card">

                        <div class="jem-home-collection-card__visual">
                            <img src="{{ asset('images/home/tailored-motion.png') }}"
                                alt="Colección Tailored Motion de JEM">

                            <span class="jem-home-collection-card__number">
                                01
                            </span>
                        </div>

                        <div class="jem-home-collection-card__content">
                            <p>JEM / 01</p>

                            <h3 class="jem-editorial-title">
                                Tailored Motion
                            </h3>

                            <span>
                                Colección para él →
                            </span>
                        </div>
                    </a>
                </div>


                {{-- Soft Structure --}}
                <div class="col-12 col-md-4">
                    <a href="{{ route('products.index', ['audience' => 'mujer']) }}" class="jem-home-collection-card">

                        <div class="jem-home-collection-card__visual">
                            <img src="{{ asset('images/home/soft-structure.png') }}" alt="Colección Soft Structure de JEM">

                            <span class="jem-home-collection-card__number">
                                02
                            </span>
                        </div>

                        <div class="jem-home-collection-card__content">
                            <p>JEM / 02</p>

                            <h3 class="jem-editorial-title">
                                Soft Structure
                            </h3>

                            <span>
                                Colección para ella →
                            </span>
                        </div>
                    </a>
                </div>


                {{-- Essential Details --}}
                <div class="col-12 col-md-4">
                    <a href="{{ route('products.index', ['group' => 'accesorios']) }}" class="jem-home-collection-card">

                        <div class="jem-home-collection-card__visual">
                            <img src="{{ asset('images/home/essential-details.png') }}"
                                alt="Selección Essential Details de JEM">

                            <span class="jem-home-collection-card__number">
                                03
                            </span>
                        </div>

                        <div class="jem-home-collection-card__content">
                            <p>JEM / 03</p>

                            <h3 class="jem-editorial-title">
                                Essential Details
                            </h3>

                            <span>
                                Calzado y accesorios →
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>


    {{-- Filosofía JEM --}}
    <section class="jem-home-statement">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 text-center">

                    <p class="jem-home-kicker mb-4">
                        JEM / Filosofía
                    </p>

                    <h2 class="jem-home-statement__title jem-editorial-title">
                        Diseñado para moverse contigo.
                    </h2>

                    <p class="jem-home-statement__text">
                        Ropa y accesorios creados a partir de siluetas limpias,
                        materiales seleccionados y detalles que completan cada look.
                    </p>

                </div>
            </div>

            <div class="jem-home-values">

                <div class="jem-home-value">
                    <span class="jem-home-value__number">
                        01
                    </span>

                    <h3>
                        SILHOUETTE
                    </h3>

                    <p>
                        Cortes limpios y proporciones contemporáneas
                        para una forma de vestir versátil.
                    </p>
                </div>

                <div class="jem-home-value">
                    <span class="jem-home-value__number">
                        02
                    </span>

                    <h3>
                        MATERIAL
                    </h3>

                    <p>
                        Texturas, tejidos y acabados seleccionados
                        para piezas que se sienten tan bien como se ven.
                    </p>
                </div>

                <div class="jem-home-value">
                    <span class="jem-home-value__number">
                        03
                    </span>

                    <h3>
                        DETAIL
                    </h3>

                    <p>
                        Calzado y accesorios que completan cada look
                        con intención, equilibrio y carácter.
                    </p>
                </div>

            </div>
    </section>


    {{-- Ofertas --}}
    @if ($saleProducts->isNotEmpty())
        <section class="jem-home-section">
            <div class="container">
                <div class="jem-home-heading">
                    <div>
                        <p class="jem-home-kicker mb-2">
                            Selección especial
                        </p>

                        <h2 class="jem-home-section-title jem-editorial-title mb-0">
                            Ofertas
                        </h2>
                    </div>

                    <a href="{{ route('products.index', ['sale' => 1]) }}" class="jem-home-link">
                        Ver todas
                    </a>
                </div>

                <div class="row g-4">
                    @foreach ($saleProducts as $product)
                        <div class="col-6 col-lg-3">
                            <article class="product-card">
                                <a href="{{ route('products.show', $product) }}" class="product-card-image">

                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            loading="lazy">
                                    @else
                                        <div class="product-placeholder">
                                            <span>JEM</span>
                                        </div>
                                    @endif

                                    <span class="product-badge">
                                        Oferta
                                    </span>
                                </a>

                                <div class="product-card-body">
                                    <p class="product-category mb-2">
                                        {{ $product->category->name }}
                                    </p>

                                    <h3 class="product-name">
                                        <a href="{{ route('products.show', $product) }}"
                                            class="text-decoration-none text-reset">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <div class="product-price">
                                        <span class="product-price-old">
                                            ₡{{ number_format($product->price, 0, ',', '.') }}
                                        </span>

                                        <span class="product-price-sale">
                                            ₡{{ number_format($product->sale_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- Cierre --}}
    <section class="jem-home-closing">
        <div class="container text-center">
            <span class="jem-home-closing__logo">
                JEM
            </span>

            <h2 class="jem-editorial-title">
                Define your presence.
            </h2>

            <p class="jem-home-closing__text">
                Ropa y accesorios contemporáneos creados para expresar tu estilo,
                con intención en cada silueta, material y detalle.
            </p>

            <a href="{{ route('products.index') }}" class="jem-home-link">
                Descubrir la colección
            </a>
        </div>
    </section>
@endsection
