@extends('layouts.app')

@section('page_title', 'Manajemen Karyawan')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Daftar Karyawan</h4>
                        <p class="card-description">
                            Kelola semua data karyawan di sini.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-custom btn-icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon-prepend icon">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah Karyawan
                        </a>
                        <!-- <a href="{{ route('employees.report') }}" class="btn btn-info btn-custom btn-icon-text ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon-prepend icon"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            Laporan
                        </a> -->
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-custom mt-3">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-custom">
                        <thead>
                            <tr>
                                <th>ID Karyawan</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Posisi</th>
                                <th>Telepon</th>
                                <th>Tanggal Bergabung</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <span class="font-weight-bold text-blue-primary">{{ $employee->employee_id }}</span>
                                </td>
                                <td>{{ $employee->user->name }}</td>
                                <td>{{ $employee->user->email }}</td>
                                <td>
                                    <span class="badge badge-info badge-custom">{{ $employee->position }}</span>
                                </td>
                                <td>{{ $employee->phone ?: '-' }}</td>
                                <td>{{ $employee->formatted_hire_date }}</td>
                                <td>
                                    <span class="badge {{ $employee->status_badge }} badge-custom">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                            class="btn btn-info btn-sm btn-custom" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="btn btn-warning btn-sm btn-custom" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M11 4h2l7 7-2 2-7-7V4z" />
                                                <path d="M18 13l-6 6H6v-6l6-6" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-custom"
                                                data-confirm="Apakah Anda yakin ingin menghapus karyawan ini?"
                                                title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                    <path d="M9 6V4h6v2" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
