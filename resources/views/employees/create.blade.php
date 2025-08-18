@extends('layouts.app')

@section('page_title', 'Tambah Karyawan Baru')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card dashboard-card">
            <div class="card-body">
                <h4 class="card-title">Tambah Karyawan Baru</h4>
                <p class="card-description">
                    Isi form di bawah ini untuk menambahkan karyawan baru.
                </p>

                <form action="{{ route('employees.store') }}" method="POST" class="form-custom">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user_id" class="form-label">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-control form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position" class="form-label">Posisi/Jabatan</label>
                        <select name="position" id="position" class="form-control form-select @error('position') is-invalid @enderror" required>
                            <option value="">Pilih Posisi</option>
                            @foreach($positions as $position)
                                <option value="{{ $position }}" {{ old('position') == $position ? 'selected' : '' }}>
                                    {{ $position }}
                                </option>
                            @endforeach
                        </select>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hire_date" class="form-label">Tanggal Bergabung</label>
                        <input type="date" name="hire_date" id="hire_date" class="form-control @error('hire_date') is-invalid @enderror" 
                               value="{{ old('hire_date') }}" required>
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="salary" class="form-label">Gaji (Rp)</label>
                        <input type="number" name="salary" id="salary" class="form-control @error('salary') is-invalid @enderror" 
                               value="{{ old('salary') }}" placeholder="Contoh: 3000000" min="0" step="1000">
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Diberhentikan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                                  placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-custom btn-icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon-prepend icon"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Karyawan
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-custom btn-icon-text ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon-prepend icon"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
