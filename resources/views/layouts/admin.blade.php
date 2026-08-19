<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JEM Store') }} · Admin</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

    <header class="admin-topbar">
        <div class="container-fluid px-4 px-lg-5">
            <div class="admin-topbar-row">

                <a href="{{ route('admin.products.index') }}" class="jem-logo admin-topbar-logo">
                    <img src="{{ asset('images/logo/jem-mark.webp') }}" alt="JEM Store" class="jem-logo-img">
                </a>

                <span class="admin-topbar-label">
                    Panel de administración
                </span>

                <nav class="admin-topbar-nav">
                    <a href="{{ route('admin.products.index') }}" class="admin-topbar-link">
                        Productos
                    </a>

                    <a href="{{ route('home') }}" class="admin-topbar-link">
                        Ver tienda
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="admin-topbar-link admin-topbar-logout">
                            Cerrar sesión
                        </button>
                    </form>
                </nav>

            </div>
        </div>
    </header>

    <main class="admin-page">
        <div class="container-fluid px-4 px-lg-5">

            @if (session('status'))
                <div class="jem-alert mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')

        </div>
    </main>

    @stack('scripts')

</body>

</html>
