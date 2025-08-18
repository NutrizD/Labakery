@extends('layouts.app')

@section('page_title', 'Detail Karyawan')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Detail Karyawan</h4>
                        <p class="card-description">
                            Informasi lengkap karyawan: <strong>{{ $employee->user->name }}</strong>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-custom btn-icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon-prepend icon"><path d="M11 4h2l7 7-2 2-7-7V4z"/><path d="M18 13l-6 6H6v-6l6-6"/><path d="M16 5l3 3"/></svg>
                            Edit
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-custom btn-icon-text ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon-prepend icon"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg>
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">ID Karyawan</label>
                            <p class="form-control-plaintext text-blue-primary font-weight-bold">{{ $employee->employee_id }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Status</label>
                            <p>
                                <span class="badge {{ $employee->status_badge }} badge-custom badge-lg">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Nama Lengkap</label>
                            <p class="form-control-plaintext">{{ $employee->user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Email</label>
                            <p class="form-control-plaintext">{{ $employee->user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Posisi/Jabatan</label>
                            <p class="form-control-plaintext">
                                <span class="badge badge-info badge-custom">{{ $employee->position }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Role Sistem</label>
                            <p class="form-control-plaintext">
                                <span class="badge badge-secondary badge-custom">{{ ucfirst($employee->user->role) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Nomor Telepon</label>
                            <p class="form-control-plaintext">{{ $employee->phone ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Tanggal Bergabung</label>
                            <p class="form-control-plaintext">{{ $employee->formatted_hire_date }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Gaji</label>
                            <p class="form-control-plaintext">{{ $employee->formatted_salary }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Lama Bekerja</label>
                            <p class="form-control-plaintext">
                                {{ $employee->hire_date->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label font-weight-bold">Alamat</label>
                    <p class="form-control-plaintext">{{ $employee->address ?: '-' }}</p>
                </div>

                @if($employee->notes)
                <div class="form-group">
                    <label class="form-label font-weight-bold">Catatan</label>
                    <p class="form-control-plaintext">{{ $employee->notes }}</p>
                </div>
                @endif

                <hr>

                <h5 class="mb-3">Riwayat Transaksi</h5>
                @if($employee->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-custom">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->transactions->take(5) as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-success badge-custom">Selesai</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <small class="text-muted">Menampilkan 5 transaksi terakhir</small>
                    </div>
                @else
                    <p class="text-muted">Belum ada transaksi yang dilakukan oleh karyawan ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
