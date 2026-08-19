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
                        Privacy Policy
                    </h1>

                    <div class="legal-content">

                        <p>
                            Esta política describe qué información recopila JEM Store, cómo se utiliza y
                            qué control tenés sobre ella. JEM Store es un proyecto académico (ver nuestro
                            <a href="{{ route('legal.disclaimer') }}">Disclaimer</a>), pero tratamos la
                            información que ingresás con el mismo cuidado que exigiríamos en un sitio real.
                        </p>

                        <h2>1. Qué información recopilamos</h2>
                        <ul>
                            <li>
                                <strong>Datos de cuenta:</strong> nombre y correo electrónico al registrarte,
                                y una contraseña que se almacena siempre cifrada (nunca en texto plano).
                            </li>
                            <li>
                                <strong>Datos de pedidos:</strong> nombre de envío, correo, teléfono y
                                dirección que ingresás en el checkout, junto con el detalle de los
                                productos comprados.
                            </li>
                            <li>
                                <strong>Cookies funcionales:</strong> una cookie de sesión para mantenerte
                                autenticado y una cookie (<code>jem_recent_products</code>) que recuerda los
                                últimos productos que visitaste, para mostrártelos en la ficha de producto.
                            </li>
                        </ul>

                        <h2>2. Para qué usamos esta información</h2>
                        <p>
                            Usamos tus datos únicamente para operar el sitio: crear y proteger tu cuenta,
                            procesar el carrito y checkout (simulado), mostrarte tu historial de pedidos, y
                            generar los reportes de ventas disponibles en tu panel de cuenta. No usamos tu
                            información con fines publicitarios ni la vendemos ni compartimos con terceros.
                        </p>

                        <h2>3. Dónde se almacena</h2>
                        <p>
                            Los datos viven en la base de datos del proyecto (SQLite), alojada en el
                            servidor donde corre esta aplicación. No se envían a servicios externos de
                            analítica, publicidad o marketing.
                        </p>

                        <h2>4. Tus derechos sobre tus datos</h2>
                        <p>
                            Podés consultar y actualizar tu nombre y correo en cualquier momento desde
                            <a href="{{ route('profile.edit') }}">tu perfil</a>. Si querés que eliminemos tu
                            cuenta y los datos asociados a ella, escribinos a
                            <a href="mailto:contacto@jemstore.com">contacto@jemstore.com</a> y lo
                            gestionamos manualmente, dado que este es un proyecto académico sin un flujo de
                            autoservicio para borrado de cuenta.
                        </p>

                        <h2>5. Seguridad</h2>
                        <p>
                            Las contraseñas se almacenan cifradas mediante algoritmos estándar de la
                            industria (bcrypt, a través del framework Laravel). El acceso al panel de
                            administración está protegido por autenticación y por un control de permisos
                            adicional que restringe esa sección solo a cuentas autorizadas.
                        </p>

                        <h2>6. Menores de edad</h2>
                        <p>
                            Este sitio no está dirigido a menores de edad y no recopilamos
                            intencionalmente información de menores.
                        </p>

                        <h2>7. Cambios a esta política</h2>
                        <p>
                            Podemos actualizar esta política conforme el proyecto evoluciona. La versión
                            vigente siempre estará disponible en esta misma página.
                        </p>

                        <h2>8. Contacto</h2>
                        <p>
                            Para cualquier consulta sobre privacidad podés escribir a
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
