@extends('layouts.app')

@section('page_title', 'Manajemen User')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pengguna</h4>
                <p class="card-description">
                    Kelola semua akun pengguna di sini. Gunakan menu "Tambah User Baru" di sidebar untuk membuat user baru.
                </p>
                @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'super_admin')
                                    <span class="badge bg-danger">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                    <span class="badge bg-primary">Admin</span>
                                    @else
                                    <span class="badge bg-success">Kasir</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            data-confirm="Hapus user '{{ $user->name }}' ({{ $user->email }})? Tindakan ini tidak dapat dibatalkan.">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada user.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
