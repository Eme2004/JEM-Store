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
                            Recuperar contraseña
                        </h1>

                        <p class="auth-subtitle mb-0">
                            Ingresa el correo asociado a tu cuenta y te enviaremos un enlace para restablecer tu contraseña.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            Te enviamos un enlace para restablecer tu contraseña.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <button type="submit" class="btn btn-dark auth-button w-100">
                            Enviar enlace de recuperación
                        </button>

                        @if (Route::has('login'))
                            <p class="text-center auth-footer mb-0">
                                ¿Recordaste tu contraseña?
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