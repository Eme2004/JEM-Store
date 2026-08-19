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
                            Mis pedidos
                        </h1>

                        <p class="account-subtitle mb-0">
                            Consulta el estado y los detalles de tus compras anteriores.
                        </p>
                    </div>


                    @if ($orders->isEmpty())

                        <div class="account-panel">
                            <div class="account-orders-empty">
                                <span class="account-orders-number">00</span>

                                <h3 class="account-orders-title">
                                    Aún no tienes pedidos
                                </h3>

                                <p class="account-orders-text mb-4">
                                    Cuando realices una compra, podrás consultar aquí
                                    el estado y los detalles de tus pedidos.
                                </p>

                                <a href="{{ route('products.index') }}" class="btn btn-dark account-button">
                                    Explorar colección
                                </a>
                            </div>
                        </div>

                    @else

                        <div class="account-panel">

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

                        </div>

                        <div class="catalog-pagination">
                            {{ $orders->onEachSide(1)->links() }}
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
