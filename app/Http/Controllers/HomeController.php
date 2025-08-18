<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Tampilkan dashboard dengan data statistik yang lengkap.
     */
    public function index()
    {
        // Data dasar
        $productCount = Product::count();
        $dailySales = Transaction::whereDate('created_at', today())->sum('total_amount');
        
        // Data tambahan untuk dashboard yang lebih informatif
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $averageTransaction = Transaction::whereDate('created_at', today())->avg('total_amount');
        
        // Data untuk grafik penjualan bulanan
        $monthlySales = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Transaksi terbaru (5 transaksi terakhir)
        $recentTransactions = Transaction::with(['user', 'details.product'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        
        // Produk terlaris berdasarkan jumlah terjual
        $bestSellingProducts = Product::with('category')
            ->withCount(['transactionDetails as total_sold' => function($query) {
                $query->select(\DB::raw('SUM(quantity)'))
                    ->from('transaction_details')
                    ->whereColumn('product_id', 'products.id');
            }])
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
        
        // Statistik tambahan
        $monthlyTotalSales = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        
        $monthlyTotalTransactions = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $topCategory = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->selectRaw('categories.name, SUM(transaction_details.quantity) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->first();
        
        // Kirim semua data yang dibutuhkan ke view 'home'
        return view('home', compact(
            'productCount',
            'dailySales',
            'monthlySales',
            'recentTransactions',
            'bestSellingProducts',
            'todayTransactions',
            'averageTransaction',
            'monthlyTotalSales',
            'monthlyTotalTransactions',
            'topCategory'
        ));
    }
}
