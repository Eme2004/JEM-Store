@extends('layouts.app')

@section('content')
<section class="account-page">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="account-header">
                    <p class="account-kicker mb-2">
                        Cuenta JEM
                    </p>

                    <h1 class="account-title mb-2">
                        Mi cuenta
                    </h1>

                    <p class="account-subtitle mb-0">
                        Administra tu información personal y consulta tus pedidos.
                    </p>
                </div>

                @if (session('status'))
                    <div class="jem-alert mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="row g-4">

                    <div class="col-12 col-lg-5">
                        <div class="account-panel account-panel-profile h-100">

                            <div>
                                <p class="account-section-label mb-2">
                                    Información personal
                                </p>

                                <h2 class="account-section-title">
                                    Tus datos
                                </h2>
                            </div>

                            <div class="account-details">
                                <div class="account-detail">
                                    <span class="account-detail-label">
                                        Nombre
                                    </span>

                                    <span class="account-detail-value">
                                        {{ $user->name }}
                                    </span>
                                </div>

                                <div class="account-detail">
                                    <span class="account-detail-label">
                                        Correo electrónico
                                    </span>

                                    <span class="account-detail-value">
                                        {{ $user->email }}
                                    </span>
                                </div>
                            </div>

                            <div class="account-actions">
                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="btn btn-dark account-button"
                                >
                                    Editar perfil
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-lg-7">
                        <div class="account-panel h-100">

                            <div class="account-panel-heading">
                                <div>
                                    <p class="account-section-label mb-2">
                                        Historial
                                    </p>

                                    <h2 class="account-section-title mb-0">
                                        Mis pedidos
                                    </h2>
                                </div>
                            </div>

                            <div class="account-orders-empty">

                                {{-- Parte 2: reemplazar este bloque por el listado real de pedidos. --}}

                                <span class="account-orders-number">
                                    00
                                </span>

                                <h3 class="account-orders-title">
                                    Aún no tienes pedidos
                                </h3>

                                <p class="account-orders-text mb-0">
                                    Cuando realices una compra, podrás consultar aquí el estado y los detalles de tus pedidos.
                                </p>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
@endsection