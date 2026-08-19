<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte de ventas - JEM Store</title>

    <style>
        @page {
            margin: 28px 32px 64px 32px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #161616;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
        }

        /* ------------------------------------------------
           Encabezado
        ------------------------------------------------ */

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .header-table td {
            padding: 0;
            vertical-align: middle;
        }

        .header-logo {
            width: 34px;
            height: auto;
        }

        .header-brand {
            padding-left: 10px;
        }

        .header-brand .name {
            color: #161616;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .header-brand .tagline {
            color: #706e6a;
            font-size: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header-right {
            text-align: right;
        }

        .header-right .report-title {
            color: #773846;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header-right .generated-at {
            margin-top: 2px;
            color: #706e6a;
            font-size: 9px;
        }

        .header-rule {
            height: 2px;
            background-color: #161616;
            margin-bottom: 14px;
        }

        /* ------------------------------------------------
           Filtros aplicados
        ------------------------------------------------ */

        .meta-bar {
            margin-bottom: 16px;
            padding: 8px 12px;
            background-color: #f5f3ef;
            border: 1px solid #eeeae4;
            color: #161616;
            font-size: 10px;
        }

        .meta-bar strong {
            color: #706e6a;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .meta-bar .sep {
            color: #eeeae4;
            padding: 0 10px;
        }

        /* ------------------------------------------------
           Indicadores (KPIs)
        ------------------------------------------------ */

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 0 -6px 18px -6px;
        }

        .kpi-cell {
            width: 25%;
            padding: 12px 10px;
            border: 1px solid #eeeae4;
            background-color: #fbfaf7;
            text-align: left;
        }

        .kpi-cell .kpi-label {
            color: #706e6a;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .kpi-cell .kpi-value {
            margin-top: 4px;
            color: #161616;
            font-size: 16px;
            font-weight: bold;
        }

        .kpi-cell.kpi-highlight {
            background-color: #161616;
            border-color: #161616;
        }

        .kpi-cell.kpi-highlight .kpi-label {
            color: #c4d8f0;
        }

        .kpi-cell.kpi-highlight .kpi-value {
            color: #ffffff;
        }

        /* ------------------------------------------------
           Detalle de ventas
        ------------------------------------------------ */

        .section-label {
            margin-bottom: 6px;
            color: #773846;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .section-rule {
            height: 1px;
            background-color: #161616;
            margin-bottom: 10px;
        }

        table.sales {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table.sales thead th {
            padding: 7px 8px;
            background-color: #161616;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.3px;
            text-align: left;
            text-transform: uppercase;
        }

        table.sales tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #eeeae4;
            font-size: 10px;
        }

        table.sales tbody tr.odd td {
            background-color: #fbfaf7;
        }

        .text-right {
            text-align: right;
        }

        /* ------------------------------------------------
           Resumen final
        ------------------------------------------------ */

        .summary-wrap {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-wrap td {
            padding: 0;
            vertical-align: top;
        }

        .summary-box {
            width: 230px;
            margin-left: auto;
            border: 1px solid #eeeae4;
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-box .summary-title {
            padding: 8px 12px;
            background-color: #f5f3ef;
            color: #706e6a;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .summary-box td.label,
        .summary-box td.value {
            padding: 5px 12px;
            font-size: 10px;
        }

        .summary-box td.value {
            text-align: right;
        }

        .summary-box tr.grand-total td {
            padding-top: 10px;
            padding-bottom: 10px;
            background-color: #161616;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
        }

        /* ------------------------------------------------
           Vacío
        ------------------------------------------------ */

        .empty {
            padding: 30px 0;
            color: #706e6a;
            font-size: 11px;
            text-align: center;
        }

        /* ------------------------------------------------
           Pie de página (se repite en cada página)
        ------------------------------------------------ */

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -46px;
            padding-top: 8px;
            border-top: 1px solid #eeeae4;
            color: #706e6a;
            font-size: 8px;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer td {
            padding: 0;
        }

        .footer .right {
            text-align: right;
        }

        .footer .center {
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- El número de página se dibuja aparte vía Canvas::page_text() en
         ReportController: el texto literal "{PAGE_NUM}" en HTML no se
         sustituye salvo que se habilite ejecución de PHP embebido en el
         PDF (enable_php), algo que no queremos activar solo por esto. --}}
    <div class="footer">
        <table>
            <tr>
                <td>© {{ now()->year }} JEM Store</td>
                <td class="center">jemstore.alwaysdata.net</td>
                <td class="right">&nbsp;</td>
            </tr>
        </table>
    </div>

    @php
        $logoPath = public_path('images/logo/jem-mark-pdf.png');
        $logoBase64 = is_file($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 34px;">
                @if ($logoBase64)
                    <img class="header-logo" src="data:image/png;base64,{{ $logoBase64 }}" alt="JEM">
                @endif
            </td>

            <td class="header-brand">
                <div class="name">JEM STORE</div>
                <div class="tagline">Contemporary clothing &amp; accessories</div>
            </td>

            <td class="header-right">
                <div class="report-title">Reporte de ventas</div>
                <div class="generated-at">Generado el {{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="header-rule"></div>

    <div class="meta-bar">
        <strong>Periodo:</strong>
        {{ ! empty($filters['month']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m', $filters['month'])->translatedFormat('F Y') : 'Todos los periodos' }}

        <span class="sep">|</span>

        <strong>Cliente:</strong>
        {{ $customer?->name ?? 'Todos los clientes' }}
    </div>

    @if ($orders->isEmpty())

        <p class="empty">No hay ventas para el filtro seleccionado.</p>

    @else

        <table class="kpi-table">
            <tr>
                <td class="kpi-cell">
                    <div class="kpi-label">Pedidos</div>
                    <div class="kpi-value">{{ $summary['count'] }}</div>
                </td>

                <td class="kpi-cell">
                    <div class="kpi-label">Subtotal</div>
                    <div class="kpi-value">₡{{ number_format($summary['subtotal'], 0, ',', '.') }}</div>
                </td>

                <td class="kpi-cell">
                    <div class="kpi-label">Impuestos</div>
                    <div class="kpi-value">₡{{ number_format($summary['tax'], 0, ',', '.') }}</div>
                </td>

                <td class="kpi-cell kpi-highlight">
                    <div class="kpi-label">Total vendido</div>
                    <div class="kpi-value">₡{{ number_format($summary['total'], 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        <div class="section-label">Detalle de ventas</div>
        <div class="section-rule"></div>

        <table class="sales">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Impuestos</th>
                    <th class="text-right">Envío</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $index => $order)
                    <tr class="{{ $index % 2 === 0 ? 'odd' : '' }}">
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td class="text-right">₡{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="text-right">₡{{ number_format($order->tax, 0, ',', '.') }}</td>
                        <td class="text-right">₡{{ number_format($order->shipping, 0, ',', '.') }}</td>
                        <td class="text-right">₡{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-wrap">
            <tr>
                <td></td>
                <td>
                    <div class="summary-box">
                        <div class="summary-title">Resumen</div>

                        <table>
                            <tr>
                                <td class="label">Pedidos</td>
                                <td class="value">{{ $summary['count'] }}</td>
                            </tr>

                            <tr>
                                <td class="label">Subtotal</td>
                                <td class="value">₡{{ number_format($summary['subtotal'], 0, ',', '.') }}</td>
                            </tr>

                            <tr>
                                <td class="label">Impuestos</td>
                                <td class="value">₡{{ number_format($summary['tax'], 0, ',', '.') }}</td>
                            </tr>

                            <tr>
                                <td class="label">Envío</td>
                                <td class="value">₡{{ number_format($summary['shipping'], 0, ',', '.') }}</td>
                            </tr>

                            <tr class="grand-total">
                                <td class="label">Total</td>
                                <td class="value">₡{{ number_format($summary['total'], 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

    @endif

</body>

</html>
