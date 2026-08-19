@extends('layouts.app')

@section('content')
    <section class="legal-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">

                    <p class="account-kicker mb-2">
                        Información legal
                    </p>

                    <h1 class="legal-title jem-editorial-title mb-4">
                        Disclaimer
                    </h1>

                    <div class="legal-content">

                        <p>
                            JEM Store es un proyecto académico desarrollado con fines educativos y de
                            portafolio. No es una tienda comercial real: los productos, precios, imágenes
                            y transacciones que aparecen en este sitio son ilustrativos y no representan
                            una operación comercial legítima.
                        </p>

                        <h2>Pagos simulados</h2>
                        <p>
                            El proceso de pago disponible en el checkout es completamente simulado. No se
                            procesan cobros reales, no se almacenan datos de tarjetas de crédito o débito
                            reales, y ninguna de las "transacciones" registradas implica movimiento de
                            dinero. Cualquier número de tarjeta, fecha de vencimiento o CVV ingresado en el
                            formulario de pago no se valida contra ninguna pasarela de pago real.
                        </p>

                        <h2>Imágenes de producto</h2>
                        <p>
                            Las fotografías de los productos provienen de bancos de imágenes de uso libre
                            (Unsplash y Pexels) y se utilizan únicamente con fines ilustrativos, para
                            representar visualmente el tipo de prenda o accesorio descrito. No corresponden
                            a fotografías profesionales de los artículos exactos descritos ni implican
                            afiliación con los fotógrafos o modelos que aparecen en ellas. Las fuentes de
                            cada imagen están documentadas en el repositorio del proyecto.
                        </p>

                        <h2>Exactitud de la información</h2>
                        <p>
                            Aunque se hace un esfuerzo razonable por mantener la información del catálogo
                            (precios, stock, descripciones) consistente, JEM Store no garantiza que esté
                            libre de errores. Este sitio se ofrece "tal cual", sin garantías de ningún tipo,
                            explícitas o implícitas.
                        </p>

                        <h2>Uso previsto</h2>
                        <p>
                            Este sitio está pensado para demostrar competencias de desarrollo web
                            full-stack (Laravel, autenticación, carrito de compras, checkout, panel de
                            administración, reportes, despliegue automatizado) en un contexto académico.
                            No debe utilizarse para realizar compras reales ni para ingresar información
                            financiera o personal sensible real.
                        </p>

                        <p class="legal-updated">
                            Última actualización: {{ now()->format('d/m/Y') }}.
                        </p>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
