@extends('layouts.app')

@section('page_title', 'Tambah Produk')

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Produk</h4>
                <p class="card-description">
                    Isi detail produk baru di bawah ini.
                </p>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Produk" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="selling_price">Harga Jual</label>
                        <input type="number" class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" placeholder="Harga Jual" value="{{ old('selling_price') }}">
                        @error('selling_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" placeholder="Stok" value="{{ old('stock') }}">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Gambar Produk</label>
                        <input type="file" name="image" class="file-upload-default @error('image') is-invalid @enderror">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Gambar">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('products.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Skrip Vanilla JS untuk mengaktifkan fungsionalitas upload file
    document.addEventListener('DOMContentLoaded', function() {
        const fileUploadBrowse = document.querySelector('.file-upload-browse');
        const fileUploadDefault = document.querySelector('.file-upload-default');
        const fileUploadInfo = document.querySelector('.file-upload-info');

        if (fileUploadBrowse && fileUploadDefault && fileUploadInfo) {
            fileUploadBrowse.addEventListener('click', function() {
                fileUploadDefault.click();
            });

            fileUploadDefault.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileUploadInfo.value = this.files[0].name;
                } else {
                    fileUploadInfo.value = '';
                }
            });
        }
    });
</script>
@endsection
