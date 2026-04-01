@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-header')@endsection
@section('page-title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-person-circle me-2 text-primary"></i>Edit Profil</div>
    <div class="card-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>
            @if($user->isMahasiswa())
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIM</label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim', $user->nim) }}">
                    @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Angkatan</label>
                    <input type="text" name="angkatan" class="form-control" value="{{ old('angkatan', $user->angkatan) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Program Studi</label>
                <input type="text" name="prodi" class="form-control" value="{{ old('prodi', $user->prodi) }}">
            </div>
            @endif
            @if($user->isDosen() || $user->isKaprodi())
            <div class="mb-3">
                <label class="form-label fw-semibold">NIP</label>
                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $user->nip) }}">
                @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @endif
            <div class="mb-4">
                <label class="form-label fw-semibold">No. HP / WhatsApp</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="cth: 08123456789">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Tanda Tangan Digital <small class="text-muted fw-normal">(untuk keperluan pengganti paraf saat cetak laporan)</small></label>
                <div class="mb-2">
                    @if($user->signature_path)
                    <div class="mb-3">
                        <p class="text-muted small mb-2">Tanda tangan saat ini:</p>
                        <img src="{{ asset('storage/' . $user->signature_path) }}" alt="Current Signature" style="max-height: 80px; border: 1px solid #ccc; padding: 5px;">
                    </div>
                    @else
                    <p class="text-muted small">Belum ada tanda tangan digital</p>
                    @endif
                </div>
                <input type="file" name="signature" class="form-control @error('signature') is-invalid @enderror" accept="image/*">
                <small class="text-muted d-block mt-2">Format: JPG, PNG, GIF | Ukuran maks: 2MB</small>
                @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <hr>
            <p class="fw-semibold mb-3">Ganti Password <small class="text-muted fw-normal">(kosongkan jika tidak ingin mengubah)</small></p>
            <div class="mb-3">
                <label class="form-label">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
