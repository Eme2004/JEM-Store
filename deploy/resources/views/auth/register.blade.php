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
                            Crear cuenta
                        </h1>

                        <p class="auth-subtitle mb-0">
                            Regístrate para comenzar a disfrutar de JEM.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label auth-label">
                                Nombre
                            </label>

                            <input
                                id="name"
                                type="text"
                                class="form-control auth-input @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

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
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label auth-label">
                                Contraseña
                            </label>

                            <input
                                id="password"
                                type="password"
                                class="form-control auth-input @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="new-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label auth-label">
                                Confirmar contraseña
                            </label>

                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control auth-input"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                            >
                        </div>

                        <button type="submit" class="btn btn-dark auth-button w-100">
                            Crear cuenta
                        </button>

                        @if (Route::has('login'))
                            <p class="text-center auth-footer mb-0">
                                ¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}" class="auth-link">
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