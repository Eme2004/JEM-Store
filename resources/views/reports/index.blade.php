@extends('layouts.app')

@section('content')
    <section class="catalog-page">
        <div class="container-fluid px-4 px-lg-5">

            <div class="catalog-header">
                <p class="catalog-kicker mb-2">
                    Panel JEM
                </p>

                <h1 class="catalog-title jem-editorial-title mb-2">
                    Reportes de ventas
                </h1>

                <p class="catalog-subtitle mb-0">
                    Filtra las ventas confirmadas por mes o por cliente y descarga el reporte en PDF.
                </p>
            </div>


            <div class="catalog-filters">

                <form method="GET" action="{{ route('reports.index') }}" class="catalog-filter-form">

                    <div>
                        <label for="month" class="catalog-filter-label">
                            Mes
                        </label>

                        <input id="month" type="month" name="month"
                            class="form-control catalog-filter-control @error('month') is-invalid @enderror"
                            value="{{ $filters['month'] ?? '' }}">

                        @error('month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="user_id" class="catalog-filter-label">
                            Cliente
                        </label>

                        <select id="user_id" name="user_id" class="form-select catalog-filter-control">
                            <option value="">Todos los clientes</option>

                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    @selected((int) ($filters['user_id'] ?? 0) === $customer->id)>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="catalog-filter-actions">

                        <button type="submit" class="btn btn-dark catalog-filter-button">
                            Filtrar
                        </button>

                        <a href="{{ route('reports.index') }}" class="catalog-clear-link">
                            Limpiar
                        </a>

                        <a href="{{ route('reports.pdf', $filters) }}" class="btn jem-outline-button">
                            Descargar PDF
                        </a>

                    </div>

                </form>
            </div>


            <div class="report-stats">

                <div class="report-stat">
                    <span class="report-stat-label">Pedidos</span>
                    <span class="report-stat-value">{{ $summary['count'] }}</span>
                </div>

                <div class="report-stat">
                    <span class="report-stat-label">Subtotal</span>
                    <span class="report-stat-value">₡{{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
                </div>

                <div class="report-stat">
                    <span class="report-stat-label">IVA</span>
                    <span class="report-stat-value">₡{{ number_format($summary['tax'], 0, ',', '.') }}</span>
                </div>

                <div class="report-stat">
                    <span class="report-stat-label">Envío</span>
                    <span class="report-stat-value">₡{{ number_format($summary['shipping'], 0, ',', '.') }}</span>
                </div>

                <div class="report-stat report-stat-highlight">
                    <span class="report-stat-label">Total vendido</span>
                    <span class="report-stat-value">₡{{ number_format($summary['total'], 0, ',', '.') }}</span>
                </div>

            </div>


            @if ($orders->isEmpty())

                <div class="catalog-empty">
                    <h2>No hay ventas para el filtro seleccionado</h2>

                    <p class="mb-0">
                        Ajusta el mes o el cliente e intenta de nuevo.
                    </p>
                </div>

            @else

                <div class="report-table-wrap">
                    <table class="table report-table align-middle">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">IVA</th>
                                <th class="text-end">Envío</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="text-end">₡{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td class="text-end">₡{{ number_format($order->tax, 0, ',', '.') }}</td>
                                    <td class="text-end">₡{{ number_format($order->shipping, 0, ',', '.') }}</td>
                                    <td class="text-end">₡{{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

        </div>
    </section>
@endsection
