@extends('layouts.app')

@section('content')
<section class="auth-page auth-page--glow">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">

                <div class="auth-card auth-card--glow">
                    <div class="text-center mb-4">
                        <span class="auth-badge">
                            <img src="{{ asset('images/logo/jem-mark.webp') }}" alt="" class="auth-badge-img">
                        </span>

                        <h1 class="auth-title auth-title--glow">
                            Crear cuenta
                        </h1>

                        <p class="auth-subtitle auth-subtitle--glow mb-0">
                            Regístrate para comenzar a disfrutar de JEM.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="visually-hidden">
                                Nombre
                            </label>

                            <div class="auth-input-icon-group">
                                <span class="auth-input-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 20c0-4 3.5-6 8-6s8 2 8 6"></path>
                                    </svg>
                                </span>

                                <input
                                    id="name"
                                    type="text"
                                    class="form-control auth-input auth-input--glow @error('name') is-invalid @enderror"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Nombre"
                                    required
                                    autocomplete="name"
                                    autofocus
                                >
                            </div>

                            @error('name')
                                <div class="auth-error--glow mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

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

                                <input
                                    id="email"
                                    type="email"
                                    class="form-control auth-input auth-input--glow @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Correo electrónico"
                                    required
                                    autocomplete="email"
                                >
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

                                <input
                                    id="password"
                                    type="password"
                                    class="form-control auth-input auth-input--glow @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="Contraseña"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>

                            @error('password')
                                <div class="auth-error--glow mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="password-strength" data-password-strength data-target="#password">
                                <div class="password-strength-bars">
                                    <span class="password-strength-bar"></span>
                                    <span class="password-strength-bar"></span>
                                    <span class="password-strength-bar"></span>
                                    <span class="password-strength-bar"></span>
                                </div>

                                <div class="password-strength-meta">
                                    <span class="password-strength-label"></span>
                                    <span class="password-strength-warning">Patrón común</span>
                                </div>

                                <ul class="password-strength-rules">
                                    <li data-rule="length" data-label="12 caracteres o más">
                                        <span class="password-strength-check"></span>
                                        12 caracteres o más
                                    </li>
                                    <li data-rule="case" data-label="mayúsculas y minúsculas">
                                        <span class="password-strength-check"></span>
                                        Mayúsculas y minúsculas
                                    </li>
                                    <li data-rule="digit" data-label="un número">
                                        <span class="password-strength-check"></span>
                                        Un número
                                    </li>
                                    <li data-rule="symbol" data-label="un símbolo">
                                        <span class="password-strength-check"></span>
                                        Un símbolo
                                    </li>
                                </ul>

                                <p class="visually-hidden" aria-live="polite" data-password-strength-announcement></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="visually-hidden">
                                Confirmar contraseña
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

                                <input
                                    id="password-confirm"
                                    type="password"
                                    class="form-control auth-input auth-input--glow"
                                    name="password_confirmation"
                                    placeholder="Confirmar contraseña"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>
                        </div>

                        <button type="submit" class="btn auth-button auth-button--glow w-100">
                            Crear cuenta →
                        </button>

                        @if (Route::has('login'))
                            <p class="text-center auth-footer auth-footer--glow mb-0">
                                ¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}" class="auth-link auth-link--glow">
                                    Iniciar sesión
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
