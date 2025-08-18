@extends('layouts.app')

@section('page_title', 'Manajemen Kategori')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kategori</h4>
                <p class="card-description">
                    Kelola kategori produk di sini.
                </p>
                <!-- Form untuk tambah kategori baru -->
                <form action="{{ route('categories.store') }}" method="POST" class="d-flex mb-3">
                    @csrf
                    <input type="text" class="form-control me-2" name="name" placeholder="Nama Kategori Baru" required>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>

                @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            data-confirm="Apakah Anda yakin ingin menghapus kategori ini? Semua produk yang terkait akan ikut terhapus.">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">Belum ada kategori.</td>
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
