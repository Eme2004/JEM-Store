@extends('layouts.app')

@section('content')
    <section class="checkout-success-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-9">

                    <div class="product-detail-back">
                        <a href="{{ route('orders.index') }}">
                            ← Volver a mis pedidos
                        </a>
                    </div>

                    <div class="checkout-success-panel">

                        <span class="checkout-success-kicker">
                            Pedido {{ $order->order_number }}
                        </span>

                        <h1 class="jem-editorial-title checkout-success-title">
                            Detalle del pedido
                        </h1>

                        <p class="checkout-success-text">
                            Realizado el {{ $order->created_at->format('d/m/Y') }}.
                        </p>

                        @include('orders._summary', ['order' => $order])


                        <div class="checkout-success-actions">
                            <a href="{{ route('orders.index') }}" class="btn btn-dark account-button">
                                Volver a mis pedidos
                            </a>

                            <a href="{{ route('products.index') }}" class="btn jem-outline-button">
                                Seguir explorando
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
