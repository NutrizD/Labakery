@extends('layouts.app')

@section('page_title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit User</h4>
                <p class="card-description">
                    Perbarui detail pengguna di bawah ini.
                </p>

                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            placeholder="Nama Lengkap"
                            value="{{ old('name', $user->name) }}"
                            required
                            autofocus>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="Alamat Email"
                            value="{{ old('email', $user->email) }}"
                            required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password">Password (kosongkan jika tidak ingin diubah)</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Konfirmasi Password">
                    </div>

                    {{-- Peran --}}
                    <div class="form-group">
                        <label for="role">Peran</label>
                        <select
                            class="form-control @error('role') is-invalid @enderror"
                            id="role"
                            name="role"
                            required>
                            <option value="" disabled>Pilih Peran</option>
                            <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                        @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto Profil --}}
                    @php
                    $rel = $user->profile_photo;
                    $exists = $rel && \Illuminate\Support\Facades\Storage::disk('public')->exists($rel);
                    $photo = $exists
                    ? \Illuminate\Support\Facades\Storage::url($rel)
                    : asset('images/faces/face28.jpg');
                    @endphp

                    <div class="form-group">
                        <label for="profile_photo">Foto Profil</label>
                        <div class="d-flex align-items-center mb-2">
                            <img id="avatarPreview"
                                src="{{ $photo }}"
                                alt="avatar"
                                width="56" height="56"
                                style="border-radius:50%;object-fit:cover;margin-right:12px;">
                            <input
                                type="file"
                                class="form-control-file @error('profile_photo') is-invalid @enderror"
                                id="profile_photo"
                                name="profile_photo"
                                accept="image/*">
                        </div>
                        <small class="form-text text-muted">Format: JPG/PNG/WEBP, maks 2MB.</small>
                        @error('profile_photo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Aksi --}}
                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview avatar saat pilih file (biarkan, tidak berkaitan dengan onerror)
    document.getElementById('profile_photo')?.addEventListener('change', function() {
        const file = this.files && this.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        const img = document.getElementById('avatarPreview');
        if (img) img.src = url;
    });
</script>
@endsection
