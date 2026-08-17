<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JEM Store') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        @php
            $announcementItems = [
                [
                    'text' => 'Envío gratis en compras mayores a ₡35.000',
                    'primary_label' => 'Ver colección',
                    'primary_url' => route('home') . '#coleccion',
                    'secondary_label' => 'Detalles',
                    'secondary_url' => route('home') . '#coleccion',
                ],
                [
                    'text' => 'Nueva colección JEM disponible por tiempo limitado',
                    'primary_label' => 'Comprar ahora',
                    'primary_url' => route('home') . '#coleccion',
                    'secondary_label' => 'Ver más',
                    'secondary_url' => route('home') . '#coleccion',
                ],
                [
                    'text' => 'Ofertas especiales en accesorios seleccionados',
                    'primary_label' => 'Ver ofertas',
                    'primary_url' => route('home') . '#coleccion',
                    'secondary_label' => 'Detalles',
                    'secondary_url' => route('home') . '#coleccion',
                ],
            ];
        @endphp

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

        <script>
            window.jemAnnouncements = @json($announcementItems);
        </script>

        
        <nav class="navbar jem-navbar navbar-expand-lg">
            <div class="container-fluid px-4 px-lg-5">

                <a class="navbar-brand jem-logo" href="{{ route('home') }}">
                    JEM
                </a>

                <button class="navbar-toggler jem-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                    aria-label="Abrir menú">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">

                    <ul class="navbar-nav jem-main-nav mx-auto">

                        <li class="nav-item">
                            <span class="nav-link jem-nav-link">
                                Novedades
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link jem-nav-link">
                                Hombre
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link jem-nav-link">
                                Mujer
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link jem-nav-link">
                                Calzado y accesorios
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link jem-nav-link jem-sale-link">
                                Ofertas
                            </span>
                        </li>

                    </ul>

                    <ul class="navbar-nav jem-utility-nav align-items-lg-center">

                        <li class="nav-item">
                            <span class="nav-link jem-icon-link" title="Buscar" aria-label="Buscar">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="M20 20L16.5 16.5"></path>
                                </svg>
                            </span>
                        </li>

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

                                <ul class="dropdown-menu dropdown-menu-end jem-dropdown" aria-labelledby="userDropdown">
                                    <li class="jem-dropdown-user">
                                        {{ Auth::user()->name }}
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            Mi cuenta
                                        </a>
                                    </li>

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

                        <li class="nav-item">
                            <span class="nav-link jem-icon-link jem-cart-icon" title="Carrito" aria-label="Carrito">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M6 8H18L19 21H5L6 8Z"></path>
                                    <path d="M9 8V6C9 4.3 10.3 3 12 3C13.7 3 15 4.3 15 6V8"></path>
                                </svg>
                            </span>
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

    </div>
</body>

</html>
