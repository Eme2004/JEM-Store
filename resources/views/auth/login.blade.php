@extends('layouts.app')

@section('content')
    <section class="auth-page auth-page--glow">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">

                    <div class="auth-card auth-card--glow">
                        <div class="text-center mb-4">
                            <span class="auth-badge">
                                <img src="{{ asset('images/logo/jem-mark.webp') }}" alt=""
                                    class="auth-badge-img">
                            </span>

                            <h1 class="auth-title auth-title--glow">
                                Bienvenido de nuevo
                            </h1>

                            <p class="auth-subtitle auth-subtitle--glow mb-0">
                                Inicia sesión para continuar en JEM Store.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="visually-hidden">
                                    Correo electrónico
                                </label>

                                <div class="auth-input-icon-group">
                                    <span class="auth-input-icon" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                            <path d="m3 7 9 6 9-6"></path>
                                        </svg>
                                    </span>

                                    <input id="email" type="email"
                                        class="form-control auth-input auth-input--glow @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="Correo electrónico"
                                        required autocomplete="email" autofocus>
                                </div>

                                @error('email')
                                    <div class="auth-error--glow mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="visually-hidden">
                                    Contraseña
                                </label>

                                <div class="auth-input-icon-group">
                                    <span class="auth-input-icon" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="4" y="11" width="16" height="9" rx="2"></rect>
                                            <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                                        </svg>
                                    </span>

                                    <input id="password" type="password"
                                        class="form-control auth-input auth-input--glow @error('password') is-invalid @enderror"
                                        name="password" placeholder="Contraseña" required
                                        autocomplete="current-password">
                                </div>

                                @error('password')
                                    <div class="auth-error--glow mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                                <div class="form-check mb-0 auth-check--glow">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label auth-small-text auth-small-text--glow"
                                        for="remember">
                                        Recordarme
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="auth-link auth-link--glow auth-small-text">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn auth-button auth-button--glow w-100">
                                Iniciar sesión →
                            </button>

                            @if (Route::has('register'))
                                <p class="text-center auth-footer auth-footer--glow mb-0">
                                    ¿No tienes una cuenta?
                                    <a href="{{ route('register') }}" class="auth-link auth-link--glow">
                                        Crear cuenta
                                    </a>
                                </p>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
