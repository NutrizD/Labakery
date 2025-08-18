<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlySalesExport;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter bulan dan tahun
        $month = (int) ($request->get('month') ?: Carbon::now()->month);
        $year = (int) ($request->get('year') ?: Carbon::now()->year);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Query transaksi pada bulan/tahun tersebut
        $transactionsQuery = Transaction::with(['details.product', 'user'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderByDesc('created_at');

        // Pagination transaksi untuk tabel atas (10 per halaman)
        $transactions = $transactionsQuery->paginate(10, ['*'], 'transactions');

        // Data summary untuk cards (tanpa pagination)
        $summaryData = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        $totalTransactions = $summaryData->count();
        $totalSales = $summaryData->sum('total_amount');

        // Total produk terjual
        $totalProducts = TransactionDetail::whereHas('transaction', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })->sum('quantity');

        // Rata-rata per transaksi
        $averagePerTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Produk terlaris dalam bulan tersebut
        $bestSellingProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('transaction', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with('product')
            ->paginate(10, ['*'], 'best_sellers');

        return view('reports.index', [
            'transactions' => $transactions,
            'bestSellingProducts' => $bestSellingProducts,
            'month' => $month,
            'year' => $year,
            'totalTransactions' => $totalTransactions,
            'totalSales' => $totalSales,
            'totalProducts' => $totalProducts,
            'averagePerTransaction' => $averagePerTransaction,
        ]);
    }

    public function transactionDetails(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');
        return view('reports.partials.transaction-details', compact('transaction'));
    }

    public function exportPdf(Request $request)
    {
        $month = (int) ($request->get('month') ?: Carbon::now()->month);
        $year  = (int) ($request->get('year')  ?: Carbon::now()->year);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $transactions = Transaction::with(['details.product', 'user'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderBy('created_at', 'asc')
            ->get();

        // Ringkasan untuk footer
        $summary = [
            'count'         => $transactions->count(),
            'total_amount'  => $transactions->sum('total_amount'),
            'total_paid'    => $transactions->sum('total_paid'),
            'total_change'  => $transactions->sum('change_amount'),
            'total_items'   => $transactions->flatMap->details->sum('quantity'),
        ];

        $html = view('reports.pdf.monthly', [
            'transactions' => $transactions,
            'month'        => $month,
            'year'         => $year,
            'start'        => $startOfMonth,
            'end'          => $endOfMonth,
            'summary'      => $summary,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans'); // font unicode aman untuk Dompdf

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-penjualan-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf"',
        ]);
    }

    public function exportExcel(Request $request)
    {
        $month = (int) ($request->get('month') ?: \Carbon\Carbon::now()->month);
        $year  = (int) ($request->get('year')  ?: \Carbon\Carbon::now()->year);

        $filename = 'laporan-penjualan-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.xlsx';

        return Excel::download(new MonthlySalesExport($month, $year), $filename);
    }
}
