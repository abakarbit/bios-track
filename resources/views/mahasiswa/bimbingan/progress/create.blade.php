@extends('layouts.app')
@section('title', 'Tambah Progress Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Tambah Progress Bimbingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Bimbingan:</strong> {{ $bimbingan->jenisLabel }} | <strong>Pembimbing {{ $bimbingan->pembimbing }}:</strong> {{ $bimbingan->dosen->name }}
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-plus me-2 text-success"></i>Form Tambah Progress Bimbingan</div>
            <div class="card-body p-4">
                <form action="{{ route('mahasiswa.bimbingan.progress.store', $bimbingan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Bimbingan <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_bimbingan" class="form-control @error('tanggal_bimbingan') is-invalid @enderror" value="{{ old('tanggal_bimbingan') }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_bimbingan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahapan Bimbingan <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="5"
                            placeholder="Tuliskan perkembangan bimbingan, hasil diskusi, tahapan yang diselesaikan, dll..." required>{{ old('catatan') }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Deskripsikan apa yang dibahas atau dicapai dalam sesi bimbingan ini.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Lampiran <small class="text-muted">(opsional)</small></label>
                        <input type="file" name="file_path" class="form-control @error('file_path') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png">
                        <div class="form-text">Format: PDF, DOC, DOCX, JPG, PNG. Maks: 5MB</div>
                        @error('file_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i>Simpan Progress</button>
                        <a href="{{ route('mahasiswa.bimbingan.show', $bimbingan) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
