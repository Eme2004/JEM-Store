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

        <nav class="navbar navbar-expand-lg bg-white border-bottom">
            <div class="container">

                <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
                    JEM
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Abrir menú"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                Inicio
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-lg-center">

                        @guest

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    Iniciar sesión
                                </a>
                            </li>

                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-dark" href="{{ route('register') }}">
                                    Registrarse
                                </a>
                            </li>

                        @else

                            <li class="nav-item dropdown">

                                <a
                                    id="userDropdown"
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    {{ Auth::user()->name }}
                                </a>

                                <ul
                                    class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="userDropdown"
                                >
                                    <li>
                                        <button
                                            class="dropdown-item"
                                            type="button"
                                            onclick="document.getElementById('logout-form').submit();"
                                        >
                                            Cerrar sesión
                                        </button>
                                    </li>
                                </ul>

                                <form
                                    id="logout-form"
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    class="d-none"
                                >
                                    @csrf
                                </form>

                            </li>

                        @endguest

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