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

                    {{-- Información personal --}}
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


                    {{-- Pedidos --}}
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

                                @if ($orders->isNotEmpty())
                                    <a href="{{ route('orders.index') }}" class="jem-mega-view-all">
                                        Ver todos
                                    </a>
                                @endif
                            </div>

                            @if ($orders->isEmpty())

                                <div class="account-orders-empty">

                                    <span class="account-orders-number">
                                        00
                                    </span>

                                    <h3 class="account-orders-title">
                                        Aún no tienes pedidos
                                    </h3>

                                    <p class="account-orders-text mb-0">
                                        Cuando realices una compra, podrás consultar aquí
                                        el estado y los detalles de tus pedidos.
                                    </p>

                                </div>

                            @else

                                <div class="account-orders-list">

                                    @foreach ($orders as $order)
                                        <a href="{{ route('orders.show', $order) }}" class="account-order-row">

                                            <div class="account-order-main">
                                                <span class="account-order-number">
                                                    {{ $order->order_number }}
                                                </span>

                                                <span class="account-order-date">
                                                    {{ $order->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>

                                            <div class="account-order-side">
                                                <span class="account-order-status">
                                                    {{ $order->status_label }}
                                                </span>

                                                <span class="account-order-total">
                                                    ₡{{ number_format($order->total, 0, ',', '.') }}
                                                </span>
                                            </div>

                                        </a>
                                    @endforeach

                                </div>

                                @if ($ordersCount > $orders->count())
                                    <p class="account-orders-more mb-0">
                                        Mostrando {{ $orders->count() }} de {{ $ordersCount }} pedidos.
                                    </p>
                                @endif

                            @endif

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
@endsection