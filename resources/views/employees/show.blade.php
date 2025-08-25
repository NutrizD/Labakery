{{-- resources/views/employees/show.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Detail Karyawan')

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="card-title mb-0">Detail Karyawan</h4>
                    <div class="btn-group">
                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                style="display:inline-block;vertical-align:-.2em;background:none"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('employees.destroy', $employee->id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    style="display:inline-block;vertical-align:-.2em;background:none"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6l-1 14H6L5 6" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M9 6V4h6v2" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                        <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">Kembali</a>
                    </div>
                </div>

                {{-- Ringkasan Atas --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-2"><small class="text-muted">Nama Lengkap</small></div>
                        <div class="h5 mb-0">{{ $employee->display_name }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2"><small class="text-muted">ID Karyawan</small></div>
                        <div class="h6 mb-0">{{ $employee->employee_id ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2"><small class="text-muted">Status</small></div>
                        @php
                        $statusMap = ['active'=>'Aktif','inactive'=>'Nonaktif','terminated'=>'Diberhentikan'];
                        $statusText = $statusMap[$employee->status] ?? ucfirst($employee->status ?? '-');
                        @endphp
                        <span class="badge {{ $employee->status_badge }}">{{ $statusText }}</span>
                    </div>
                </div>

                <hr>

                {{-- Detail Utama --}}
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Jenis Kelamin</dt>
                            <dd class="col-sm-7">
                                @if($employee->gender === 'male') Laki-laki
                                @elseif($employee->gender === 'female') Perempuan
                                @else - @endif
                            </dd>

                            <dt class="col-sm-5">Posisi/Jabatan</dt>
                            <dd class="col-sm-7">{{ $employee->position ?? '-' }}</dd>

                            <dt class="col-sm-5">Tanggal Bergabung</dt>
                            <dd class="col-sm-7">{{ $employee->formatted_hire_date }}</dd>

                            <dt class="col-sm-5">Gaji</dt>
                            <dd class="col-sm-7">{{ $employee->formatted_salary }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Telepon</dt>
                            <dd class="col-sm-7">{{ $employee->phone ?? '-' }}</dd>

                            <dt class="col-sm-5">Alamat</dt>
                            <dd class="col-sm-7">{{ $employee->address ?? '-' }}</dd>

                            <dt class="col-sm-5">Ditautkan ke User</dt>
                            <dd class="col-sm-7">
                                @if($employee->user)
                                {{ $employee->user->name }} <small class="text-muted">({{ $employee->user->email }})</small>
                                @else
                                <span class="text-muted">Tidak ditautkan</span>
                                @endif
                            </dd>

                            <dt class="col-sm-5">Catatan</dt>
                            <dd class="col-sm-7">{{ $employee->notes ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

                @if($employee->transactions && $employee->transactions->count())
                <hr>
                <div class="mt-3">
                    <h5 class="mb-2">Aktivitas Transaksi (Ringkas)</h5>
                    <div class="small text-muted mb-2">
                        Total transaksi sebagai kasir: {{ number_format($employee->transactions->count()) }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Tanggal</th>
                                    <th>No. Invoice</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->transactions->take(10) as $trx)
                                <tr>
                                    <td>{{ $trx->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $trx->invoice_number }}</td>
                                    <td class="text-right">Rp{{ number_format($trx->total_amount) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($employee->transactions->count() > 10)
                        <div class="text-muted small">Menampilkan 10 terbaru dari {{ number_format($employee->transactions->count()) }} transaksi.</div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Kartu ringkas samping (opsional) --}}
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Ringkasan</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Nama</span>
                        <strong>{{ $employee->display_name }}</strong>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Gender</span>
                        <strong>
                            @if($employee->gender === 'male') Laki-laki
                            @elseif($employee->gender === 'female') Perempuan
                            @else - @endif
                        </strong>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Posisi</span>
                        <strong>{{ $employee->position ?? '-' }}</strong>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Tgl Bergabung</span>
                        <strong>{{ $employee->formatted_hire_date }}</strong>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Status</span>
                        <strong class="{{ $employee->status_badge }} badge">{{ $statusText }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Override sederhana untuk badge jika tema mengunci warna --}}
<style>
    .badge-success {
        background-color: #28a745;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-danger {
        background-color: #dc3545;
    }
</style>
@endsection
