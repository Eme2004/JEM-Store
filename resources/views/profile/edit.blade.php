@extends('layouts.app')

@section('content')
    <section class="account-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">

                    <div class="account-header">
                        <p class="account-kicker mb-2">
                            Cuenta JEM
                        </p>

                        <h1 class="account-title mb-2">
                            Editar perfil
                        </h1>

                        <p class="account-subtitle mb-0">
                            Actualiza la información asociada a tu cuenta.
                        </p>
                    </div>

                    <div class="profile-edit-panel">

                        <div class="profile-edit-heading">
                            <p class="account-section-label mb-2">
                                Información personal
                            </p>

                            <h2 class="account-section-title mb-0">
                                Tus datos
                            </h2>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="form-label auth-label">
                                    Nombre
                                </label>

                                <input id="name" type="text"
                                    class="form-control auth-input @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

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

                                <input id="email" type="email"
                                    class="form-control auth-input @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email', $user->email) }}" required autocomplete="email">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="profile-edit-actions">
                                <button type="submit" class="btn btn-dark account-button">
                                    Guardar cambios
                                </button>

                                <a href="{{ route('profile.show') }}" class="btn jem-outline-button">
                                    Cancelar
                                </a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
