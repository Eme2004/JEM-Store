@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">

                <div class="auth-card">
                    <div class="text-center mb-5">
                        <img src="{{ asset('images/logo/jem-mark.webp') }}" alt="JEM Store" class="auth-brand-img mb-3">

                        <h1 class="auth-title">
                            Nueva contraseña
                        </h1>

                        <p class="auth-subtitle mb-0">
                            Crea una nueva contraseña para volver a acceder a tu cuenta.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4">
                            <label for="email" class="form-label auth-label">
                                Correo electrónico
                            </label>

                            <input
                                id="email"
                                type="email"
                                class="form-control auth-input @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ $email ?? old('email') }}"
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

                        <div class="mb-4">
                            <label for="password" class="form-label auth-label">
                                Nueva contraseña
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
                                Confirmar nueva contraseña
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
                            Guardar nueva contraseña
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection