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
                        Terms of Use
                    </h1>

                    <div class="legal-content">

                        <p>
                            Estos términos de uso regulan el acceso y uso de JEM Store. Al navegar,
                            registrarte o realizar un pedido en este sitio, aceptás los términos descritos
                            a continuación. Si no estás de acuerdo, te pedimos no utilizar el sitio.
                        </p>

                        <h2>1. Naturaleza del sitio</h2>
                        <p>
                            JEM Store es un proyecto académico y de portafolio. No constituye una relación
                            comercial real entre las personas usuarias y una empresa. Ver también nuestro
                            <a href="{{ route('legal.disclaimer') }}">Disclaimer</a>.
                        </p>

                        <h2>2. Cuentas de usuario</h2>
                        <p>
                            Para comprar, guardar pedidos o acceder a reportes es necesario crear una
                            cuenta con un correo electrónico válido y una contraseña. Sos responsable de
                            mantener la confidencialidad de tus credenciales y de toda actividad realizada
                            desde tu cuenta. Podés editar tu información personal en cualquier momento
                            desde tu perfil.
                        </p>

                        <h2>3. Productos, precios y stock</h2>
                        <p>
                            Los precios se muestran en colones costarricenses (₡) e incluyen los impuestos
                            aplicables según se detalla en el checkout. El catálogo, los precios y la
                            disponibilidad de stock pueden cambiar sin previo aviso, dado el carácter
                            demostrativo del proyecto.
                        </p>

                        <h2>4. Pedidos y pagos</h2>
                        <p>
                            El proceso de compra (carrito, checkout, confirmación) es funcional a nivel de
                            aplicación, pero el pago es simulado: no se realizan cobros reales ni se
                            procesan pagos a través de una pasarela real. Un pedido "confirmado" en este
                            sitio no genera ninguna obligación de envío físico de mercancía.
                        </p>

                        <h2>5. Uso aceptable</h2>
                        <p>
                            Al usar JEM Store te comprometés a no intentar vulnerar la seguridad del sitio,
                            no usar cuentas ajenas sin autorización, no automatizar solicitudes de forma
                            abusiva y no utilizar el sitio con fines distintos a la exploración y evaluación
                            del proyecto.
                        </p>

                        <h2>6. Propiedad intelectual</h2>
                        <p>
                            El nombre "JEM Store", el logotipo y la identidad visual del sitio son parte de
                            este proyecto académico. Las fotografías de producto pertenecen a sus autores
                            originales (ver el <a href="{{ route('legal.disclaimer') }}">Disclaimer</a> para
                            más detalle) y se usan bajo licencias de uso libre.
                        </p>

                        <h2>7. Limitación de responsabilidad</h2>
                        <p>
                            JEM Store se ofrece "tal cual" y sin garantías de disponibilidad continua,
                            exactitud de la información o ausencia de errores. En ningún caso las personas
                            responsables del proyecto serán responsables por daños derivados del uso o la
                            imposibilidad de uso del sitio.
                        </p>

                        <h2>8. Cambios a estos términos</h2>
                        <p>
                            Estos términos pueden actualizarse conforme evoluciona el proyecto. La versión
                            vigente siempre estará disponible en esta misma página.
                        </p>

                        <h2>9. Contacto</h2>
                        <p>
                            Para consultas sobre estos términos podés escribir a
                            <a href="mailto:contacto@jemstore.com">contacto@jemstore.com</a>.
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
