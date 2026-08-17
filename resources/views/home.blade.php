@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="container py-5 text-center">
            <p class="text-uppercase fw-semibold mb-2">
                Nueva colección
            </p>

            <h1 class="display-2 jem-editorial-title mb-3">
                JEM Store
            </h1>

            <p class="lead text-secondary mx-auto mb-4" style="max-width: 650px;">
                Moda y accesorios para un estilo moderno, simple y auténtico.
            </p>

            <a href="#coleccion" class="btn btn-dark btn-lg px-4">
                Ver colección
            </a>
        </div>
    </section>

    <section id="coleccion" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="jem-editorial-title">
                    Descubre JEM
                </h2>

                <p class="text-secondary mb-0">
                    Nuestra colección de ropa y accesorios estará disponible próximamente.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="bg-white p-5 text-center h-100 border">
                        <h3 class="h4">
                            Hombre
                        </h3>

                        <p class="text-secondary mb-0">
                            Prendas esenciales para todos los días.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="bg-white p-5 text-center h-100 border">
                        <h3 class="h4">
                            Mujer
                        </h3>

                        <p class="text-secondary mb-0">
                            Diseños modernos con un estilo limpio.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="bg-white p-5 text-center h-100 border">
                        <h3 class="h4">
                            Accesorios
                        </h3>

                        <p class="text-secondary mb-0">
                            Detalles para completar cada estilo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
