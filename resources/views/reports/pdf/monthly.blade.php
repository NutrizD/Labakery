@php
use Carbon\Carbon;

// Nama periode (contoh: "Agustus 2025")
$bulanNama = Carbon::create($year, $month, 1)->locale('id')->translatedFormat('F Y');

// Helper rupiah
function rupiah($v){ return 'Rp'.number_format((int)$v,0,',','.'); }

// Ringkasan
$grandTotal = $transactions->sum('total_amount');
$totalProducts = $transactions->flatMap->details->sum('quantity');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan {{ $bulanNama }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1,
        h2,
        h3 {
            margin: 0 0 6px 0;
        }

        .small {
            font-size: 10px;
            color: #666;
        }

        .muted {
            color: #666;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mb-12 {
            margin-bottom: 12px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #f3f5f9;
            font-weight: 700;
        }

        tfoot td {
            font-weight: 700;
            background: #fafafa;
        }

        /* Header tabel terulang tiap halaman (Dompdf) */
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Lebar kolom agar stabil */
        .col-no {
            width: 10mm;
        }

        .col-tgl {
            width: 30mm;
        }

        .col-inv {
            width: 35mm;
        }

        .col-kasir {
            width: 30mm;
        }

        .col-jml {
            width: 22mm;
        }

        .col-total {
            width: 32mm;
        }

        /* Footer page number */
        .page-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -5px;
            height: 18px;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="mb-12">
        <div class="row">
            <div>
                <h2>Laporan Penjualan</h2>
                <div class="small">Periode: {{ $bulanNama }}</div>
            </div>
            <div class="text-right small muted">
                Dicetak: {{ now()->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB<br>
                Total Transaksi: {{ $transactions->count() }} | Total Item: {{ $totalProducts }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">No</th>
                <th class="col-tgl">Tanggal</th>
                <th class="col-inv">No. Invoice</th>
                <th class="col-kasir">Kasir</th>
                <th class="col-jml text-center">Jumlah Produk</th>
                <th class="col-total text-right">Total Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $transaction)
            @php
            $productCount = $transaction->details->sum('quantity');
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $transaction->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                <td>{{ $transaction->invoice_number }}</td>
                <td>{{ $transaction->user->name ?? '-' }}</td>
                <td class="text-center">{{ $productCount }}</td>
                <td class="text-right">{{ rupiah($transaction->total_amount) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center muted">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-center">{{ $totalProducts }}</td>
                <td class="text-right">{{ rupiah($grandTotal) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Nomor halaman Dompdf --}}
    <div class="page-footer text-right">
        <script type="text/php">
            if (isset($pdf)) {
                $pdf->page_text(520, 820, "Halaman {PAGE_NUM} / {PAGE_COUNT}", "Helvetica", 8, [0.33,0.33,0.33]);
            }
        </script>
    </div>

</body>

</html>
