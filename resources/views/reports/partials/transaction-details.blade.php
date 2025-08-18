@extends('layouts.app')

@section('page_title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Detail Transaksi</h4>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg> Kembali</a>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Tanggal</strong></td>
                                <td>{{ $transaction->created_at->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kasir</strong></td>
                                <td>{{ $transaction->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Pembayaran</strong></td>
                                <td>Rp{{ number_format($transaction->total_amount) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga Jual</th>
                                <th>Sub total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $i => $detail)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $detail->product->name ?? '-' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp{{ number_format($detail->price) }}</td>
                                    <td>Rp{{ number_format($detail->price * $detail->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


