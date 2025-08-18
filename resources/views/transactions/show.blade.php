@extends('layouts.app')

@section('page_title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary me-2 icon"><path d="M21 2v20l-3-2-3 2-3-2-3 2-3-2V2h18z"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>
                        Detail Transaksi
                    </h4>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg> Kembali
                    </a>
                </div>

                <!-- Informasi Transaksi -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="card-title mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 icon"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="8"/></svg>
                                    Informasi Transaksi
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>No. Invoice:</strong></td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $transaction->invoice_number }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            {{ $transaction->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kasir:</strong></td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            {{ $transaction->user->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-success">Selesai</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="card-title mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 icon"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><line x1="6" y1="15" x2="10" y2="15"/></svg>
                                    Informasi Pembayaran
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>Total Belanja:</strong></td>
                                        <td class="text-end">
                                            <span class="fs-5 fw-bold text-danger">
                                                Rp{{ number_format($transaction->total_amount) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibayar:</strong></td>
                                        <td class="text-end">
                                            <span class="fs-5 fw-bold text-primary">
                                                Rp{{ number_format($transaction->total_paid) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kembalian:</strong></td>
                                        <td class="text-end">
                                            <span class="fs-5 fw-bold text-success">
                                                Rp{{ number_format($transaction->change_amount) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Produk -->
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 icon"><path d="M16.5 9.4L7.5 4.21 12 2l9 5-4.5 2.4z"/><path d="M3 7l9 5 9-5"/><path d="M12 22V12"/></svg>
                            Detail Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Produk</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaction->details as $index => $detail)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($detail->product->image)
                                                        <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                             alt="{{ $detail->product->name }}" 
                                                             class="rounded me-3" 
                                                             style="width: 45px; height: 45px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 45px; height: 45px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white fs-5 icon"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong class="d-block">{{ $detail->product->name }}</strong>
                                                        @if($detail->product->category)
                                                            <small class="text-muted">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 icon"><path d="M20.59 13.41L12 22l-8-8 8-8 8.59 8.41z"/><circle cx="7.5" cy="14.5" r="1.5"/></svg>
                                                                {{ $detail->product->category->name }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">Rp{{ number_format($detail->price) }}</span>
                                            </td>
                                            <td class="text-center">
                                            <span class="badge bg-secondary fs-6">{{ $detail->quantity }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-success">
                                                    Rp{{ number_format($detail->price * $detail->quantity) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted fs-1 d-block mb-2 icon"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5 12V5h14v7"/><path d="M5 19h14"/></svg>
                                                <span class="text-muted">Tidak ada detail produk.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="4" class="text-end">
                                            <strong class="fs-5">Total:</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="fs-4 text-danger">
                                                Rp{{ number_format($transaction->total_amount) }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="text-center mt-4">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-lg me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 icon"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg> Kembali ke Riwayat
                    </a>
                    <button onclick="window.print()" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 icon"><path d="M6 9V2h12v7"/><path d="M6 18h12v4H6z"/><rect x="6" y="12" width="12" height="6"/><path d="M18 14h.01"/></svg> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
