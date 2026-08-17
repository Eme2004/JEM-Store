@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">

                <div class="auth-card">
                    <div class="text-center mb-5">
                        <p class="auth-brand mb-3">JEM</p>

                        <h1 class="auth-title">
                            Iniciar sesión
                        </h1>

                        <p class="auth-subtitle mb-0">
                            Accede a tu cuenta para continuar.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label auth-label">
                                Correo electrónico
                            </label>

                            <input
                                id="email"
                                type="email"
                                class="form-control auth-input @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label auth-label">
                                Contraseña
                            </label>

                            <input
                                id="password"
                                type="password"
                                class="form-control auth-input @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                            <div class="form-check mb-0">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                >

                                <label class="form-check-label auth-small-text" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="auth-link auth-small-text"
                                >
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-dark auth-button w-100">
                            Iniciar sesión
                        </button>

                        @if (Route::has('register'))
                            <p class="text-center auth-footer mb-0">
                                ¿No tienes una cuenta?
                                <a href="{{ route('register') }}" class="auth-link">
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