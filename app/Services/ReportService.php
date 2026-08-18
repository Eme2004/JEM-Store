<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ReportService
{
    /**
     * Consulta base de ventas: solo pedidos pagados, filtrables por
     * mes (formato Y-m) y por cliente.
     */
    public function query(array $filters): Builder
    {
        $query = Order::with('user')
            ->where('payment_status', 'paid');

        if (! empty($filters['month'])) {
            [$year, $month] = explode('-', $filters['month']);

            $query->whereYear('created_at', (int) $year)
                ->whereMonth('created_at', (int) $month);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest();
    }

    public function summarize(Collection $orders): array
    {
        return [
            'count' => $orders->count(),
            'subtotal' => (float) $orders->sum('subtotal'),
            'tax' => (float) $orders->sum('tax'),
            'shipping' => (float) $orders->sum('shipping'),
            'total' => (float) $orders->sum('total'),
        ];
    }
}
