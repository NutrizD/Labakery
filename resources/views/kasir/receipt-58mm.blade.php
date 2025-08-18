<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Struk</title>
    <style>
        /* ==== Thermal paper size ==== */
        @page {
            size: 58mm auto;
            margin: 2mm;
        }

        body {
            width: 58mm;
            margin: 0 auto;
        }

        /* ==== Typography & helpers ==== */
        * {
            font-family: "Courier New", ui-monospace, SFMono-Regular, Menlo, monospace;
            font-variant-numeric: tabular-nums;
        }

        .receipt {
            padding: 2mm 2mm 4mm;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .small {
            font-size: 10px;
            line-height: 1.25;
        }

        .normal {
            font-size: 12px;
            line-height: 1.25;
        }

        .big {
            font-size: 14px;
            font-weight: 700;
        }

        hr.dashed {
            border: 0;
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .row .label {
            flex: 1;
            white-space: pre-wrap;
        }

        .row .value {
            flex: 1;
            text-align: right;
        }

        .items .item {
            display: grid;
            grid-template-columns: 1fr auto;
            column-gap: 8px;
            margin: 2px 0;
        }

        .items .meta {
            grid-column: 1 / -1;
            font-size: 10px;
        }

        .bold {
            font-weight: 700;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="receipt">
        @php
        use Carbon\Carbon;

        Carbon::setLocale('id');
        $tz = config('app.timezone', 'Asia/Jakarta');

        $trxTime = optional($transaction->created_at)->timezone($tz)->isoFormat('DD MMM YYYY HH:mm:ss');
        $printTime = ($printedAt ?? now())->timezone($tz)->isoFormat('DD MMM YYYY HH:mm:ss');
        @endphp



        <!-- ===== Header Toko ===== -->
        <div class="center">
            @if(!empty($logo))
            <img src="{{ $logo }}" alt="Logo" height="36">
            @endif
            <div class="big">{{ $store['name'] ?? config('app.name') }}</div>
            @if(!empty($store['address']))
            <div class="small">{{ $store['address'] }}</div>
            @endif
            @if(!empty($store['website']))
            <div class="small">Website : {{ $store['website'] }}</div>
            @endif
            @if(!empty($store['instagram']))
            <div class="small">instagram : {{ $store['instagram'] }}</div>
            @endif
        </div>

        <hr class="dashed">

        <!-- ===== Info Transaksi ===== -->
        <div class="small">
            <div class="row"><span class="label">No. Nota :</span><span class="value">{{ $transaction->invoice_number ?? $transaction->id }}</span></div>
            <div class="row"><span class="label">Waktu    :</span><span class="value">{{ optional($transaction->created_at)->format('Y-m-d H:i:s') }}</span></div>
            <div class="row"><span class="label">Kasir    :</span><span class="value">{{ $transaction->user->name ?? '-' }}</span></div>
        </div>

        <hr class="dashed">

        <!-- ===== Daftar Item ===== -->
        <div class="row normal bold" style="margin-bottom:2px;">
            <span class="label">Item</span>
            <span class="value">Total</span>
        </div>

        <div class="items normal">
            @foreach($transaction->details as $d)
            @php
            $qty = (int)($d->quantity ?? 0);
            $price = (int)($d->price ?? 0);
            $lineTotal = $qty * $price;
            @endphp
            <div class="item">
                <div>{{ $d->product->name ?? $d->name ?? 'Item' }} - {{ $qty }} x {{ $d->variant ?? '' }}</div>
                <div class="right">{{ number_format($lineTotal, 0, '.', ',') }}</div>
                <div class="meta">@ {{ $qty }} x {{ number_format($price, 0, '.', ',') }}</div>
            </div>
            @endforeach
        </div>

        <hr class="dashed">

        <!-- ===== Ringkasan (tanpa pajak) ===== -->
        @php
        $subtotal = $transaction->details->sum(fn($d) => (int)($d->quantity ?? 0) * (int)($d->price ?? 0));
        $grand = (int)($transaction->total_amount ?? $subtotal); // no tax/PB1
        $paid = (int)($transaction->total_paid ?? $grand);
        $change = (int)($transaction->change_amount ?? max(0, $paid - $grand));
        @endphp

        <div class="small">
            <div class="row"><span class="label">Subtotal {{ $transaction->details->count() }} item</span><span class="value">{{ number_format($subtotal, 0, '.', ',') }}</span></div>
            <div class="row bold"><span class="label">Total Tagihan</span><span class="value">{{ number_format($grand, 0, '.', ',') }}</span></div>
        </div>

        <hr class="dashed">

        <!-- ===== Pembayaran ===== -->
        <div class="small">
            <div class="row"><span class="label">Jenis Pembayaran</span><span class="value">{{ $transaction->payment_method ?? 'Tunai' }}</span></div>
            <div class="row"><span class="label">Total Bayar</span><span class="value">{{ number_format($paid, 0, '.', ',') }}</span></div>
            <div class="row"><span class="label">Kembalian</span><span class="value">{{ number_format($change, 0, '.', ',') }}</span></div>
        </div>

        <hr class="dashed">

        <!-- ===== Footer ===== -->
        <div class="center normal bold" style="letter-spacing:1px;">PAID</div>
    </div>
</body>

</html>
