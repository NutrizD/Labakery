@extends('layouts.app')

@section('page_title', 'Laporan Penjualan')

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Laporan Penjualan Bulanan</h4>
                <p class="card-description">Filter per bulan/tahun, ekspor PDF/Excel, dan lihat detail transaksi.</p>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-0">Total Transaksi</h6>
                                        <h4 class="mb-0">{{ $totalTransactions }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M21 2v20l-3-2-3 2-3-2-3 2-3-2V2h18z" />
                                            <line x1="8" y1="6" x2="16" y2="6" />
                                            <line x1="8" y1="10" x2="16" y2="10" />
                                            <line x1="8" y1="14" x2="12" y2="14" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-0">Total Penjualan</h6>
                                        <h4 class="mb-0">Rp{{ number_format($totalSales) }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <rect x="2" y="6" width="20" height="12" rx="2" />
                                            <circle cx="12" cy="12" r="2" />
                                            <path d="M6 12h0M18 12h0" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-black">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-0">Total Produk</h6>
                                        <h4 class="mb-0">{{ $totalProducts }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M16.5 9.4L7.5 4.21 12 2l9 5-4.5 2.4z" />
                                            <path d="M3 7l9 5 9-5" />
                                            <path d="M12 22V12" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-0">Rata-rata</h6>
                                        <h4 class="mb-0">Rp{{ number_format($averagePerTransaction) }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <polyline points="3 17 9 11 13 15 21 7" />
                                            <polyline points="14 7 21 7 21 14" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M22 3H2l8 9v7l4 2v-9z" />
                            </svg> Filter Laporan</h6>
                        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Bulan</label>
                                <select name="month" class="form-select form-select-lg">
                                    @for($m=1;$m<=12;$m++)
                                        <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->monthName }}</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tahun</label>
                                <select name="year" class="form-select form-select-lg">
                                    @for($y=now()->year-5;$y<=now()->year+1;$y++)
                                        <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                                {{-- Tampilkan (submit) --}}
                                <button type="submit" class="btn btn-primary" id="btn-show">
                                    <i class="ti-search me-1"></i> Tampilkan
                                </button>

                                {{-- Export PDF (tetap) --}}
                                <a href="{{ route('reports.export.pdf', ['month' => $month, 'year' => $year]) }}"
                                    class="btn btn-danger" id="btn-export-pdf">
                                    <i class="ti-file me-1"></i> Export PDF
                                </a>

                                {{-- Export Excel (samakan gaya) --}}
                                <a href="{{ route('reports.export.excel', ['month' => $month, 'year' => $year]) }}"
                                    class="btn btn-success" id="btn-export-excel">
                                    <i class="ti-download me-1"></i> Export Excel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <line x1="8" y1="6" x2="21" y2="6" />
                                <line x1="8" y1="12" x2="21" y2="12" />
                                <line x1="8" y1="18" x2="21" y2="18" />
                                <line x1="3" y1="6" x2="3" y2="6" />
                                <line x1="3" y1="12" x2="3" y2="12" />
                                <line x1="3" y1="18" x2="3" y2="18" />
                            </svg> Data Transaksi</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 60px;">No</th>
                                        <th style="width: 180px;">Tanggal</th>
                                        <th style="width: 150px;">No. Invoice</th>
                                        <th style="width: 150px;">Kasir</th>
                                        <th class="text-center" style="width: 120px;">Jumlah Produk</th>
                                        <th class="text-end" style="width: 150px;">Total Pembayaran</th>
                                        <th class="text-center" style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $index => $transaction)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $transactions->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $transaction->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                                <small class="text-muted">{{ $transaction->created_at->locale('id')->isoFormat('HH:mm') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $transaction->invoice_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <!-- <div class="avatar-title bg-primary rounded-circle">
                                                        {{ substr($transaction->user->name ?? 'U', 0, 1) }}
                                                    </div> -->
                                                </div>
                                                <span>{{ $transaction->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $transaction->details->sum('quantity') }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">Rp{{ number_format($transaction->total_amount) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('reports.transaction.details', $transaction->id) }}"
                                                class="btn btn-outline-primary btn-sm"
                                                title="Lihat Detail"
                                                data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <path d="M22 12h-6l-2 3h-4l-2-3H2" />
                                                    <path d="M5 12V5h14v7" />
                                                    <path d="M5 19h14" />
                                                </svg>
                                                <p class="mt-2 mb-0">Tidak ada data laporan untuk periode ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $transactions->firstItem() ?? 0 }} sampai {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} hasil
                    </div>
                    <div class="pagination-wrapper">
                        {{ $transactions->appends(['month'=>$month,'year'=>$year])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Produk Terlaris Bulan Ini</h4>
                <p class="card-description">
                    Daftar 5 produk terlaris berdasarkan kuantitas penjualan.
                </p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellingProducts as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                <td>{{ $item->total_quantity }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada data produk terlaris.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $bestSellingProducts->firstItem() ?? 0 }} sampai {{ $bestSellingProducts->lastItem() ?? 0 }} dari {{ $bestSellingProducts->total() }} hasil
                    </div>
                    <div class="pagination-wrapper">
                        {{ $bestSellingProducts->appends(['month'=>$month,'year'=>$year])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Add loading state to export buttons
        document.querySelectorAll('a[href*="export"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                var originalText = this.innerHTML;
                this.innerHTML = '<i class="ti-loader ti-spin me-2"></i>Memproses...';
                this.style.pointerEvents = 'none';

                // Reset after 3 seconds if no redirect happens
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 3000);
            });
        });

        // Add loading state to filter form
        document.querySelector('form[action*="reports"]').addEventListener('submit', function(e) {
            const summaryCards = document.querySelectorAll('.card.bg-primary, .card.bg-success, .card.bg-info, .card.bg-warning');
            summaryCards.forEach(function(card) {
                card.classList.add('loading');
            });
        });

        // Add smooth scrolling to pagination
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

    // Add animation to summary cards
    function animateValue(element, start, end, duration, isCurrency = false) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);

            if (isCurrency) {
                element.innerHTML = 'Rp' + current.toLocaleString();
            } else {
                element.innerHTML = current.toLocaleString();
            }

            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Animate summary cards on page load
    window.addEventListener('load', function() {
        // Remove loading state
        const summaryCards = document.querySelectorAll('.card.bg-primary, .card.bg-success, .card.bg-info, .card.bg-warning');
        summaryCards.forEach(function(card) {
            card.classList.remove('loading');
        });

        const summaryValues = document.querySelectorAll('.card.bg-primary h4, .card.bg-success h4, .card.bg-info h4, .card.bg-warning h4');
        summaryValues.forEach(function(card) {
            const text = card.textContent;
            // Handle different number formats (with Rp, commas, etc.)
            let number;
            if (text.includes('Rp')) {
                // For currency values
                number = parseInt(text.replace(/[^\d]/g, ''));
            } else {
                // For regular numbers
                number = parseInt(text.replace(/[^\d]/g, ''));
            }

            if (!isNaN(number) && number > 0) {
                const originalText = card.textContent;
                if (text.includes('Rp')) {
                    card.textContent = 'Rp0';
                } else {
                    card.textContent = '0';
                }

                setTimeout(() => {
                    animateValue(card, 0, number, 1000, originalText.includes('Rp'));
                }, 500);
            }
        });
    });
</script>
@endsection
