<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Halaman Kasir (server-side search + sorting + pagination + filter kategori)
     */
    public function kasir(Request $request)
    {
        $q        = trim($request->query('q', ''));
        $sort     = $request->query('sort', 'name-asc');
        $category = $request->query('category'); // id kategori (nullable)

        // whitelist kolom & arah
        $sortMap = [
            'name-asc'   => ['name',          'asc'],
            'name-desc'  => ['name',          'desc'],
            'price-asc'  => ['selling_price', 'asc'],
            'price-desc' => ['selling_price', 'desc'],
            'stock-asc'  => ['stock',         'asc'],
            'stock-desc' => ['stock',         'desc'],
            'newest'     => ['created_at',    'desc'],
        ];
        if (!isset($sortMap[$sort])) {
            $sort = 'name-asc';
        }
        [$col, $dir] = $sortMap[$sort];

        $products = Product::query()
            ->where('stock', '>', 0)
            ->when($q, function ($query) use ($q) {
                $like = '%' . $q . '%';
                $query->where('name', 'like', $like); // hanya nama
            })
            ->when($category, function ($query) use ($category) {
                // Asumsi products memiliki kolom category_id
                $query->where('category_id', $category);
                // Jika relasi many-to-many: whereHas('categories', fn($q)=>$q->where('categories.id', $category));
            })
            ->orderBy($col, $dir)
            ->paginate(24);

        // bawa q/sort/category ke pagination
        $products->appends($request->only(['q', 'sort', 'category']));

        // data dropdown kategori
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('kasir.index', [
            'products'   => $products,
            'q'          => $q,
            'sort'       => $sort,
            'category'   => $category,
            'categories' => $categories,
        ]);
    }

    /**
     * Simpan transaksi dari halaman kasir
     * - Hitung harga dari DB (abaikan harga & total client)
     * - Cek stok atomik (lockForUpdate) dalam DB::transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart_items'               => 'required|array|min:1',
            'cart_items.*.product_id'  => 'required|integer|exists:products,id',
            'cart_items.*.quantity'    => 'required|integer|min:1',
            'total_paid'               => 'required|integer|min:0',
        ]);

        $items = collect($validated['cart_items']);
        $ids   = $items->pluck('product_id')->unique()->values();

        $result = DB::transaction(function () use ($items, $ids, $validated) {
            $products = Product::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');

            $computedTotal = 0;
            $lineItems = [];

            foreach ($items as $row) {
                $productId = (int) $row['product_id'];
                $qty       = (int) $row['quantity'];

                $product = $products->get($productId);
                if (!$product) {
                    abort(422, "Produk #{$productId} tidak ditemukan.");
                }
                if ($qty < 1) {
                    abort(422, "Kuantitas untuk {$product->name} tidak valid.");
                }
                if ($product->stock < $qty) {
                    abort(422, "Stok '{$product->name}' tidak mencukupi. Sisa: {$product->stock}");
                }

                $price    = (int) $product->selling_price;
                $subtotal = $price * $qty;

                $computedTotal += $subtotal;

                $lineItems[] = [
                    'product'  => $product,
                    'quantity' => $qty,
                    'price'    => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $totalPaid = (int) $validated['total_paid'];
            if ($totalPaid < $computedTotal) {
                abort(422, "Uang dibayar kurang. Total: {$computedTotal}, Dibayar: {$totalPaid}");
            }

            $invoice = $this->generateInvoiceNumber();

            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'user_id'        => Auth::id(),
                'total_amount'   => $computedTotal,
                'total_paid'     => $totalPaid,
                'change_amount'  => $totalPaid - $computedTotal,
            ]);

            foreach ($lineItems as $li) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $li['product']->id,
                    'quantity'       => $li['quantity'],
                    'price'          => $li['price'],
                ]);

                $li['product']->decrement('stock', $li['quantity']);
            }

            return $transaction->load('details.product');
        });

        return response()->json([
            'message'     => 'Transaksi berhasil!',
            'transaction' => $result,
        ], 201);
    }

    public function index(Request $request)
    {
        $query = Transaction::with('user', 'details.product')->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'details.product']);
        return view('transactions.show', compact('transaction'));
    }

    public function receipt(Transaction $transaction): mixed
    {
        $transaction->load(['user', 'details.product']);
        $store = [
            'name'      => 'La Gramma',
            'address'   => 'Jl. Gajah Mada No. 153A, Benua Melayu Darat, Kec. Pontianak Selatan, Kota Pontianak, Kalimantan Barat 78121',
            'instagram' => 'lagrammahomemade',
        ];
        $printedAt = now();
        return view('kasir.receipt-58mm', compact('transaction', 'store', 'printedAt'));
    }

    protected function generateInvoiceNumber(): string
    {
        do {
            $candidate = 'INV-' . strtoupper(Str::random(10));
        } while (Transaction::where('invoice_number', $candidate)->exists());

        return $candidate;
    }
}
