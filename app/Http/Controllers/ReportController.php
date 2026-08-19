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
        ])->setPaper('a4', 'portrait');

        $this->drawPageNumbers($pdf);

        return $pdf->download(
            'reporte-ventas-'.now()->format('Ymd-His').'.pdf'
        );
    }

    /**
     * Dibuja "Pág. X de Y" en el pie de cada página. Se hace vía la API de
     * Canvas de dompdf (no con el texto literal "{PAGE_NUM}" en el HTML)
     * para no tener que activar enable_php solo por esto: ese HTML se
     * evalúa como PHP embebido dentro del propio PDF, un riesgo que no
     * vale la pena para un número de página.
     */
    private function drawPageNumbers(\Barryvdh\DomPDF\PDF $pdf): void
    {
        $pdf->render();

        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans');

        $canvas->page_text(
            $canvas->get_width() - 90,
            $canvas->get_height() - 24,
            'Pág. {PAGE_NUM} de {PAGE_COUNT}',
            $font,
            8,
            [0.44, 0.43, 0.42]
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
