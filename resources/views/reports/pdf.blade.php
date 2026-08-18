<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte de ventas - JEM Store</title>

    <style>
        body {
            margin: 0;
            padding: 30px;
            color: #161616;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }

        .brand {
            margin-bottom: 4px;
            color: #773846;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        h1 {
            margin: 0 0 18px;
            font-size: 16px;
            font-weight: bold;
        }

        .meta {
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eeeae4;
        }

        .meta p {
            margin: 0 0 4px;
            font-size: 11px;
            color: #706e6a;
        }

        .meta strong {
            color: #161616;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        thead th {
            padding: 6px 8px;
            border-bottom: 1px solid #161616;
            color: #161616;
            font-size: 10px;
            text-align: left;
            text-transform: uppercase;
        }

        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #eeeae4;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            width: 260px;
            margin-left: auto;
        }

        .totals td {
            padding: 4px 8px;
            font-size: 11px;
        }

        .totals .grand-total td {
            padding-top: 8px;
            border-top: 1px solid #161616;
            font-size: 13px;
            font-weight: bold;
        }

        .empty {
            padding: 20px 0;
            color: #706e6a;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="brand">JEM STORE</div>
    <h1>Reporte de ventas</h1>

    <div class="meta">
        <p>
            <strong>Periodo:</strong>
            {{ ! empty($filters['month']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m', $filters['month'])->format('m/Y') : 'Todos los periodos' }}
        </p>

        <p>
            <strong>Cliente:</strong>
            {{ $customer?->name ?? 'Todos los clientes' }}
        </p>

        <p>
            <strong>Generado:</strong>
            {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    @if ($orders->isEmpty())

        <p class="empty">No hay ventas para el filtro seleccionado.</p>

    @else

        <table>
            <thead>
                <tr>
                    <th>Número pedido</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Impuestos</th>
                    <th class="text-right">Envío</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                    <tr>
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

        <table class="totals">
            <tr>
                <td>Pedidos</td>
                <td class="text-right">{{ $summary['count'] }}</td>
            </tr>

            <tr>
                <td>Subtotal</td>
                <td class="text-right">₡{{ number_format($summary['subtotal'], 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td>Impuestos</td>
                <td class="text-right">₡{{ number_format($summary['tax'], 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td>Envío</td>
                <td class="text-right">₡{{ number_format($summary['shipping'], 0, ',', '.') }}</td>
            </tr>

            <tr class="grand-total">
                <td>TOTAL VENDIDO</td>
                <td class="text-right">₡{{ number_format($summary['total'], 0, ',', '.') }}</td>
            </tr>
        </table>

    @endif

</body>

</html>
