<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reports)
    {
    }

    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);

        $orders = $this->reports->query($filters)->get();
        $summary = $this->reports->summarize($orders);

        $customers = User::whereHas('orders')
            ->orderBy('name')
            ->get();

        return view('reports.index', [
            'orders' => $orders,
            'summary' => $summary,
            'customers' => $customers,
            'filters' => $filters,
        ]);
    }

    public function pdf(Request $request)
    {
        $filters = $this->validateFilters($request);

        $orders = $this->reports->query($filters)->get();
        $summary = $this->reports->summarize($orders);

        $customer = ! empty($filters['user_id'])
            ? User::find($filters['user_id'])
            : null;

        $pdf = Pdf::loadView('reports.pdf', [
            'orders' => $orders,
            'summary' => $summary,
            'filters' => $filters,
            'customer' => $customer,
        ]);

        return $pdf->download(
            'reporte-ventas-'.now()->format('Ymd-His').'.pdf'
        );
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
    }
}
