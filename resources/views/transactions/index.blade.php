@extends('layouts.app')

@section('page_title', 'Riwayat Transaksi')

@section('content')


<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Riwayat Transaksi</h4>
                <p class="card-description">
                    Daftar semua transaksi yang pernah terjadi. Klik tombol aksi untuk melihat detail atau cetak struk.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">No</th>
                                <th style="width: 150px;">No. Invoice</th>
                                <th style="width: 150px;">Kasir</th>
                                <th class="text-end" style="width: 120px;">Jumlah Total</th>
                                <th class="text-end" style="width: 120px;">Dibayar</th>
                                <th class="text-end" style="width: 120px;">Kembalian</th>
                                <th style="width: 120px;">Tanggal</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $transaction)
                            <tr>
                                <td class="text-center fw-bold">{{ $transactions->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $transaction->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <!-- <div class="avatar-title bg-primary rounded-circle">
                                                {{ substr($transaction->user->name, 0, 1) }}
                                            </div> -->
                                        </div>
                                        <span>{{ $transaction->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-success">Rp{{ number_format($transaction->total_amount) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-primary">Rp{{ number_format($transaction->total_paid) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-warning">Rp{{ number_format($transaction->change_amount) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $transaction->created_at->locale('id')->isoFormat('D MMM YYYY') }}</span>
                                        <small class="text-muted">{{ $transaction->created_at->locale('id')->isoFormat('HH:mm') }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-primary btn-sm" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-info btn-sm" title="Cetak Struk" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M6 9V2h12v7" />
                                                <path d="M6 18h12v4H6z" />
                                                <rect x="6" y="12" width="12" height="6" />
                                                <path d="M18 14h.01" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M22 12h-6l-2 3h-4l-2-3H2" />
                                            <path d="M5 12V5h14v7" />
                                            <path d="M5 19h14" />
                                        </svg>
                                        <p class="mt-2 mb-0">Tidak ada riwayat transaksi.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
                        dari {{ $transactions->total() }} transaksi
                    </div>
                    <nav aria-label="Transaksi pagination">
                        {{ $transactions->onEachSide(1)->links() }} {{-- otomatis pakai Bootstrap --}}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add smooth scrolling to pagination
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.pagination .page-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Smooth scroll to top of content
                document.querySelector('.content-wrapper').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    });
</script>
@endsection
