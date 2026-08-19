@extends('layouts.app')

@section('content')
    <section class="checkout-success-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-9">

                    <div class="checkout-success-panel">

                        <span class="checkout-success-kicker">
                            Pedido confirmado
                        </span>

                        <h1 class="jem-editorial-title checkout-success-title">
                            Gracias por tu compra
                        </h1>

                        <p class="checkout-success-text">
                            Te enviamos la confirmación a {{ $order->shipping_email }}.
                            Guarda tu número de pedido y de seguimiento.
                        </p>

                        @include('orders._summary', ['order' => $order])


                        <div class="checkout-success-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-dark account-button">
                                Seguir explorando
                            </a>

                            <a href="{{ route('profile.show') }}" class="btn jem-outline-button">
                                Ver mi cuenta
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
