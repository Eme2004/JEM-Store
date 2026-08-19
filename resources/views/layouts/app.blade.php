<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JEM Store') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">

        @php
            $announcementItems = [
                [
                    'text' => 'Envío gratis en compras mayores a ₡35.000',
                    'primary_label' => 'Ver colección',
                    'primary_url' => route('products.index'),
                    'secondary_label' => 'Descubrir JEM',
                    'secondary_url' => route('home') . '#coleccion',
                ],
                [
                    'text' => 'Nueva colección JEM disponible por tiempo limitado',
                    'primary_label' => 'Comprar ahora',
                    'primary_url' => route('products.index'),
                    'secondary_label' => 'Ver más',
                    'secondary_url' => route('home') . '#coleccion',
                ],
                [
                    'text' => 'Ofertas especiales en productos seleccionados',
                    'primary_label' => 'Ver colección',
                    'primary_url' => route('products.index'),
                    'secondary_label' => 'Descubrir',
                    'secondary_url' => route('products.index'),
                ],
            ];
        @endphp


        {{-- ==================================================
            HEADER
        ================================================== --}}
        <header class="jem-header">

            {{-- Barra de anuncios --}}
            <div class="jem-announcement-bar" data-announcement-bar>
                <div class="jem-announcement-content">

                    <span class="jem-announcement-text" data-announcement-text>
                        {{ $announcementItems[0]['text'] }}
                    </span>

                    <a href="{{ $announcementItems[0]['primary_url'] }}" class="jem-announcement-link"
                        data-announcement-primary>
                        {{ $announcementItems[0]['primary_label'] }}
                    </a>

                    <a href="{{ $announcementItems[0]['secondary_url'] }}" class="jem-announcement-link"
                        data-announcement-secondary>
                        {{ $announcementItems[0]['secondary_label'] }}
                    </a>

                </div>
            </div>


            {{-- Navegación principal --}}
            <nav class="navbar jem-navbar navbar-expand-lg">
                <div class="container-fluid px-4 px-lg-5">

                    {{-- Logo --}}
                    <a class="navbar-brand jem-logo" href="{{ route('home') }}">
                        <img src="{{ asset('images/logo/jem-mark.webp') }}" alt="JEM Store" class="jem-logo-img">
                    </a>


                    {{-- Menú móvil --}}
                    <button class="navbar-toggler jem-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                        aria-label="Abrir menú">
                        <span class="navbar-toggler-icon"></span>
                    </button>


                    <div class="collapse navbar-collapse" id="mainNavbar">

                        {{-- Navegación principal --}}
                        <ul class="navbar-nav jem-main-nav mx-auto">

                            {{-- Novedades --}}
                            <li class="nav-item">
                                <a href="{{ route('products.index', ['new' => 1]) }}" class="nav-link jem-nav-link">
                                    Novedades
                                </a>
                            </li>


                            {{-- Hombre --}}
                            <li class="nav-item dropdown jem-mega-item">

                                <a href="#" class="nav-link jem-nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    Hombre
                                </a>

                                <div class="dropdown-menu jem-mega-menu">
                                    <div class="container-fluid px-4 px-lg-5">

                                        <div class="jem-mega-header">
                                            <div>
                                                <span class="jem-mega-kicker">
                                                    JEM Hombre
                                                </span>

                                                <h2 class="jem-mega-title jem-editorial-title">
                                                    Hombre
                                                </h2>
                                            </div>

                                            <a href="{{ route('products.index', ['audience' => 'hombre']) }}"
                                                class="jem-mega-view-all">
                                                Ver todo Hombre
                                            </a>
                                        </div>

                                        <div class="row g-4">

                                            @foreach ($menMenuCategories as $group)
                                                @if ($group->children->isNotEmpty())
                                                    <div class="col-12 col-md-4">

                                                        <a href="{{ route('products.index', [
                                                            'audience' => 'hombre',
                                                            'group' => $group->slug,
                                                        ]) }}"
                                                            class="jem-mega-group">
                                                            {{ $group->name }}
                                                        </a>

                                                        <ul class="jem-mega-list">

                                                            @foreach ($group->children as $category)
                                                                <li>
                                                                    <a
                                                                        href="{{ route('products.index', [
                                                                            'audience' => 'hombre',
                                                                            'category' => $category->slug,
                                                                        ]) }}">
                                                                        {{ $category->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach

                                                        </ul>

                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>

                                    </div>
                                </div>

                            </li>


                            {{-- Mujer --}}
                            <li class="nav-item dropdown jem-mega-item">

                                <a href="#" class="nav-link jem-nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    Mujer
                                </a>

                                <div class="dropdown-menu jem-mega-menu">
                                    <div class="container-fluid px-4 px-lg-5">

                                        <div class="jem-mega-header">
                                            <div>
                                                <span class="jem-mega-kicker">
                                                    JEM Mujer
                                                </span>

                                                <h2 class="jem-mega-title jem-editorial-title">
                                                    Mujer
                                                </h2>
                                            </div>

                                            <a href="{{ route('products.index', ['audience' => 'mujer']) }}"
                                                class="jem-mega-view-all">
                                                Ver todo Mujer
                                            </a>
                                        </div>

                                        <div class="row g-4">

                                            @foreach ($womenMenuCategories as $group)
                                                @if ($group->children->isNotEmpty())
                                                    <div class="col-12 col-md-4">

                                                        <a href="{{ route('products.index', [
                                                            'audience' => 'mujer',
                                                            'group' => $group->slug,
                                                        ]) }}"
                                                            class="jem-mega-group">
                                                            {{ $group->name }}
                                                        </a>

                                                        <ul class="jem-mega-list">

                                                            @foreach ($group->children as $category)
                                                                <li>
                                                                    <a
                                                                        href="{{ route('products.index', [
                                                                            'audience' => 'mujer',
                                                                            'category' => $category->slug,
                                                                        ]) }}">
                                                                        {{ $category->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach

                                                        </ul>

                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>

                                    </div>
                                </div>

                            </li>


                            {{-- Calzado y accesorios --}}
                            <li class="nav-item dropdown jem-mega-item">

                                <a href="#" class="nav-link jem-nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    Calzado y accesorios
                                </a>

                                <div class="dropdown-menu jem-mega-menu">
                                    <div class="container-fluid px-4 px-lg-5">

                                        <div class="jem-mega-header">
                                            <div>
                                                <span class="jem-mega-kicker">
                                                    Complementa tu estilo
                                                </span>

                                                <h2 class="jem-mega-title jem-editorial-title">
                                                    Calzado y accesorios
                                                </h2>
                                            </div>
                                        </div>

                                        <div class="row g-4">

                                            @foreach ($shopMenuCategories as $group)
                                                <div class="col-12 col-md-6">

                                                    <a href="{{ route('products.index', [
                                                        'group' => $group->slug,
                                                    ]) }}"
                                                        class="jem-mega-group">
                                                        {{ $group->name }}
                                                    </a>

                                                    <ul class="jem-mega-list">

                                                        @foreach ($group->children as $category)
                                                            <li>
                                                                <a
                                                                    href="{{ route('products.index', [
                                                                        'category' => $category->slug,
                                                                    ]) }}">
                                                                    {{ $category->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach

                                                    </ul>

                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                </div>

                            </li>


                            {{-- Ofertas --}}
                            <li class="nav-item">
                                <a href="{{ route('products.index', ['sale' => 1]) }}"
                                    class="nav-link jem-nav-link jem-sale-link">
                                    Ofertas
                                </a>
                            </li>

                        </ul>

                        {{-- Acciones --}}
                        <ul class="navbar-nav jem-utility-nav align-items-lg-center">

                            {{-- Buscar --}}
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}#search" class="nav-link jem-icon-link"
                                    title="Buscar" aria-label="Buscar">
                                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <path d="M20 20L16.5 16.5"></path>
                                    </svg>
                                </a>
                            </li>


                            {{-- Cuenta --}}
                            @guest

                                <li class="nav-item">
                                    <a class="nav-link jem-icon-link" href="{{ route('login') }}" title="Mi cuenta"
                                        aria-label="Mi cuenta">
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="8" r="4"></circle>
                                            <path d="M4.5 21C5.3 16.9 8 15 12 15C16 15 18.7 16.9 19.5 21"></path>
                                        </svg>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item dropdown">

                                    <a id="userDropdown" class="nav-link jem-icon-link" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false" title="Mi cuenta"
                                        aria-label="Mi cuenta">
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="8" r="4"></circle>
                                            <path d="M4.5 21C5.3 16.9 8 15 12 15C16 15 18.7 16.9 19.5 21"></path>
                                        </svg>
                                    </a>


                                    <ul class="dropdown-menu dropdown-menu-end jem-dropdown"
                                        aria-labelledby="userDropdown">

                                        <li class="jem-dropdown-user">
                                            {{ Auth::user()->name }}
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                                Mi cuenta
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('orders.index') }}">
                                                Mis pedidos
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('reports.index') }}">
                                                Reportes de ventas
                                            </a>
                                        </li>

                                        @if (Auth::user()->is_admin)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                                    Panel de administración
                                                </a>
                                            </li>
                                        @endif

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf

                                                <button type="submit" class="dropdown-item">
                                                    Cerrar sesión
                                                </button>
                                            </form>
                                        </li>

                                    </ul>

                                </li>

                            @endguest


                            {{-- Carrito --}}
                            <li class="nav-item">
                                <a href="{{ route('cart.index') }}" class="nav-link jem-icon-link jem-cart-icon"
                                    title="Carrito" aria-label="Carrito">
                                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M6 8H18L19 21H5L6 8Z"></path>
                                        <path d="M9 8V6C9 4.3 10.3 3 12 3C13.7 3 15 4.3 15 6V8"></path>
                                    </svg>

                                    @if ($cartCount > 0)
                                        <span class="jem-cart-count">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>

                        </ul>

                    </div>
                </div>
            </nav>

        </header>


        {{-- Datos para la barra rotativa --}}
        <script>
            window.jemAnnouncements = @json($announcementItems);
        </script>


        {{-- ==================================================
            CONTENIDO PRINCIPAL
        ================================================== --}}
        <main>
            @yield('content')
        </main>


        {{-- ==================================================
    JEM MEMBERS
================================================== --}}
        <section class="jem-join">
            <div class="container text-center">

                <span class="jem-join__brand">
                    JEM / MEMBERS
                </span>

                <h2 class="jem-editorial-title">
                    Join JEM. Get 10% off.
                </h2>

                <p>
                    Crea tu cuenta y recibe un 10% de descuento en tu primera compra.
                    Descubre nuevas colecciones, piezas seleccionadas y novedades JEM.
                </p>

                @guest
                    <a href="{{ route('register') }}" class="jem-join__button">
                        Unirme a JEM
                    </a>
                @else
                    <a href="{{ route('products.index') }}" class="jem-join__button">
                        Explorar colección
                    </a>
                @endguest

                <span class="jem-join__note">
                    * Beneficio de bienvenida aplicable a la primera compra.
                    Sujeto a condiciones.
                </span>

            </div>
        </section>


        {{-- ==================================================
    FOOTER JEM
================================================== --}}
        <footer class="jem-footer">
            <div class="container-fluid px-4 px-lg-5">

                <div class="row gy-5">

                    {{-- Marca --}}
                    <div class="col-12 col-lg-4">

                        <a href="{{ route('home') }}" class="jem-footer-logo">
                            <img src="{{ asset('images/logo/jem-logo-white.webp') }}" alt="JEM Store" class="jem-footer-logo-img">
                        </a>

                        <p class="jem-footer-description">
                            Ropa y accesorios contemporáneos creados alrededor
                            de siluetas limpias, materiales seleccionados
                            y una identidad propia.
                        </p>

                        <span class="jem-footer-signature">
                            Define your presence.
                        </span>

                    </div>


                    {{-- Tienda --}}
                    <div class="col-6 col-md-3 col-lg-2">

                        <h2 class="jem-footer-title">
                            Tienda
                        </h2>

                        <ul class="jem-footer-list">

                            <li>
                                <a href="{{ route('home') }}">
                                    Inicio
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('products.index') }}">
                                    Colección
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('products.index', ['new' => 1]) }}">
                                    Novedades
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('products.index', ['audience' => 'hombre']) }}">
                                    Hombre
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('products.index', ['audience' => 'mujer']) }}">
                                    Mujer
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('products.index', ['sale' => 1]) }}">
                                    Ofertas
                                </a>
                            </li>

                        </ul>

                    </div>


                    {{-- Cuenta --}}
                    <div class="col-6 col-md-3 col-lg-2">

                        <h2 class="jem-footer-title">
                            Cuenta
                        </h2>

                        <ul class="jem-footer-list">

                            @guest
                                <li>
                                    <a href="{{ route('login') }}">
                                        Iniciar sesión
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('register') }}">
                                        Crear cuenta
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('profile.show') }}">
                                        Mi cuenta
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('profile.edit') }}">
                                        Editar perfil
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('orders.index') }}">
                                        Mis pedidos
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('reports.index') }}">
                                        Reportes de ventas
                                    </a>
                                </li>
                            @endguest

                        </ul>
                    </div>

                    {{-- Métodos de pago --}}
                    <div class="jem-footer-payment-section">
                        <span class="jem-footer-payment-title">
                            Métodos de pago
                        </span>

                        <div class="jem-footer-payment-list">

                            <div class="jem-payment-method jem-payment-visa">
                                VISA
                            </div>

                            <div class="jem-payment-method">
                                <span class="jem-mastercard-circles">
                                    <span></span>
                                    <span></span>
                                </span>

                                <span>Mastercard</span>
                            </div>

                            <div class="jem-payment-method">
                                SINPE Móvil
                            </div>

                        </div>
                    </div>

                    {{-- Contacto --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <h2 class="jem-footer-title">
                            Contacto
                        </h2>

                        <address class="jem-footer-contact">

                            <div class="jem-footer-contact__item">
                                <span class="jem-footer-contact__label">
                                    Ubicación
                                </span>

                                <span>
                                    La Legua, Porvenir, Alajuela, Costa Rica
                                </span>
                            </div>

                            <div class="jem-footer-contact__item">
                                <span class="jem-footer-contact__label">
                                    Teléfono
                                </span>

                                <a href="tel:+50600000000">
                                    +506 6019-0694
                                </a>
                            </div>

                            <div class="jem-footer-contact__item">
                                <span class="jem-footer-contact__label">
                                    Correo
                                </span>

                                <a href="mailto:contacto@jemstore.com">
                                    contacto@jemstore.com
                                </a>
                            </div>

                            <div class="jem-footer-contact__item">
                                <span class="jem-footer-contact__label">
                                    Atención
                                </span>

                                <span>
                                    Lunes – sábado<br>
                                    9:00 a.m. – 6:00 p.m.
                                </span>
                            </div>

                        </address>

                    </div>

                </div>


                {{-- Parte inferior --}}
                <div class="jem-footer-bottom">

                    <div>
                        <p class="mb-1">
                            © {{ now()->year }} JEM Store
                        </p>

                        <p class="mb-0">
                            La Legua, Porvenir, Alajuela, Costa Rica
                        </p>
                    </div>

                    <div class="jem-footer-bottom__right">
                        <span>
                            Todos los derechos reservados.
                        </span>

                        <span>
                            JEM / Contemporary clothing & accessories
                        </span>
                    </div>

                </div>


                {{-- Línea legal --}}
                <div class="jem-footer-legal">
                    © Copyright {{ now()->year }} by JEM Store
                    <span aria-hidden="true">|</span>
                    <a href="{{ route('legal.disclaimer') }}">Disclaimer</a>
                    <span aria-hidden="true">|</span>
                    <a href="{{ route('legal.terms') }}">Terms of Use</a>
                    <span aria-hidden="true">|</span>
                    <a href="{{ route('legal.privacy') }}">Privacy Policy</a>
                    <span aria-hidden="true">|</span>
                    Website &amp; Branding by Emesis Mairena y Jairo Herrera
                </div>

            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
