@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #1a3c6e 0%, #2a9d8f 100%)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="text-center mb-4">
                    <i class="bi bi-mortarboard-fill text-warning" style="font-size:2.5rem"></i>
                    <h3 class="text-white fw-bold mt-2">Daftar Akun Mahasiswa</h3>
                </div>
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST" action="/register">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                                    @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                                    <input type="text" name="prodi" class="form-control @error('prodi') is-invalid @enderror" value="{{ old('prodi') }}" required>
                                    @error('prodi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Angkatan <span class="text-danger">*</span></label>
                                    <input type="text" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan') }}" placeholder="2021" required>
                                    @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">No. HP</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xx">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-person-plus me-2"></i>Daftar Akun
                            </button>
                        </form>
                        <hr>
                        <p class="text-center mb-0 small">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
