{{-- resources/views/employees/index.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Manajemen Karyawan')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="card-title mb-0">Daftar Karyawan</h4>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="ti-user mr-1"></i> Tambah Karyawan
                    </a>
                </div>

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Posisi</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th>Tgl Bergabung</th>
                                <th class="text-right">Gaji</th>
                                <th style="width: 160px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            @php
                            $no = ($employees->firstItem() ?? 1) + $loop->index;
                            $genderLabel = $employee->gender === 'male'
                            ? 'Laki-laki'
                            : ($employee->gender === 'female' ? 'Perempuan' : '-');
                            $statusClass = $employee->status_badge; // dari accessor model
                            $statusText = [
                            'active' => 'Aktif',
                            'inactive' => 'Nonaktif',
                            'terminated' => 'Diberhentikan',
                            ][$employee->status] ?? ucfirst($employee->status ?? '-');
                            @endphp
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $employee->display_name }}</td>
                                <td>{{ $genderLabel }}</td>
                                <td>{{ $employee->position ?? '-' }}</td>
                                <td>{{ $employee->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>{{ $employee->formatted_hire_date }}</td>
                                <td class="text-right">{{ $employee->formatted_salary }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        {{-- Lihat (opsional, jika route show ada) --}}
                                        @if(Route::has('employees.show'))
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                            class="btn btn-info btn-sm btn-custom" title="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                style="display:inline-block;vertical-align:-.2em;background:none"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        @endif

                                        {{-- Edit --}}
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="btn btn-warning btn-sm btn-custom" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                style="display:inline-block;vertical-align:-.2em;background:none"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        {{-- Hapus (sesuai permintaan: tetap pakai confirm alert) --}}
                                        <form action="{{ route('employees.destroy', $employee->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm btn-custom"
                                                title="Hapus"
                                                aria-label="Hapus"
                                                data-confirm="Hapus karyawan '{{ $employee->display_name ?? $employee->full_name ?? optional($employee->user)->name }}'? Tindakan ini tidak dapat dibatalkan.">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    style="display:inline-block;vertical-align:-.2em;background:none"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14H6L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                    <path d="M9 6V4h6v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $employees->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sedikit gaya agar badge terlihat konsisten bila tema override --}}
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
