@extends('layouts.app')

@section('page_title', 'Edit Karyawan')

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Karyawan</h4>
                <p class="card-description">Perbarui detail karyawan di bawah.</p>

                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap (WAJIB) --}}
                    <div class="form-group">
                        <label for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="full_name" name="full_name"
                            class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $employee->full_name) }}" required autofocus>
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Jenis Kelamin (WAJIB) --}}
                    <div class="form-group">
                        <label class="d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                        @php $current = old('gender', $employee->gender); @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="gender_male" name="gender" value="male"
                                {{ $current === 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gender_male">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="gender_female" name="gender" value="female"
                                {{ $current === 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gender_female">Perempuan</label>
                        </div>
                        @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- (Opsional) Tautkan ke User --}}
                    {{-- <div class="form-group">
            <label for="user_id">Tautkan ke User (opsional)</label>
            <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror">
              <option value="">— Tidak ditautkan —</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}"
                    {{ (string)old('user_id', $employee->user_id) === (string)$u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->email }})
                    </option>
                    @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div> --}}

            {{-- Posisi/Jabatan --}}
            <div class="form-group">
                <label for="position">Posisi/Jabatan</label>
                <select id="position" name="position" class="form-control @error('position') is-invalid @enderror">
                    <option value="">— Pilih Posisi —</option>
                    @foreach($positions as $pos)
                    <option value="{{ $pos }}" {{ old('position', $employee->position) === $pos ? 'selected' : '' }}>
                        {{ $pos }}
                    </option>
                    @endforeach
                </select>
                @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Telepon --}}
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="text" id="phone" name="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $employee->phone) }}">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Alamat --}}
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address"
                    class="form-control @error('address') is-invalid @enderror"
                    rows="2">{{ old('address', $employee->address) }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tanggal Bergabung --}}
            <div class="form-group">
                <label for="hire_date">Tanggal Bergabung</label>
                <input type="date" id="hire_date" name="hire_date"
                    class="form-control @error('hire_date') is-invalid @enderror"
                    value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}">
                @error('hire_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Gaji --}}
            <div class="form-group">
                <label for="salary">Gaji (Rp)</label>
                <input type="number" id="salary" name="salary"
                    class="form-control @error('salary') is-invalid @enderror"
                    value="{{ old('salary', $employee->salary) }}" min="0" step="1">
                @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                    @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" {{ old('status', $employee->status) === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Catatan --}}
            <div class="form-group">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">
                {{ old('notes', $employee->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
            <a href="{{ route('employees.index') }}" class="btn btn-light">Batal</a>
            </form>

        </div>
    </div>
</div>
</div>
@endsection
