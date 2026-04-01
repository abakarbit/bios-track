@extends('layouts.app')
@section('title', 'Edit Progress Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Edit Progress Bimbingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Bimbingan:</strong> {{ $bimbingan->jenisLabel }} | <strong>Pembimbing {{ $bimbingan->pembimbing }}:</strong> {{ $bimbingan->dosen->name }}
        </div>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Catatan:</strong> Anda hanya bisa mengedit progress yang belum diparaf dosen. Setelah diparaf, tidak bisa diubah lagi.
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2 text-warning"></i>Form Edit Progress Bimbingan</div>
            <div class="card-body p-4">
                <form action="{{ route('mahasiswa.bimbingan.progress.update', $progress) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Bimbingan <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_bimbingan" class="form-control @error('tanggal_bimbingan') is-invalid @enderror" value="{{ old('tanggal_bimbingan', $progress->tanggal_bimbingan?->format('Y-m-d\TH:i')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_bimbingan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahapan Bimbingan <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="5"
                            placeholder="Tuliskan perkembangan bimbingan, hasil diskusi, tahapan yang diselesaikan, dll..." required>{{ old('catatan', $progress->catatan) }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Deskripsikan apa yang dibahas atau dicapai dalam sesi bimbingan ini.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Lampiran <small class="text-muted">(opsional)</small></label>
                        @if($progress->file_path)
                            <div class="alert alert-secondary mb-3">
                                <i class="bi bi-file me-2"></i>
                                <strong>File saat ini:</strong>
                                <a href="{{ Storage::url($progress->file_path) }}" target="_blank" class="ms-2">Lihat File</a>
                                <br>
                                <small class="text-muted">Jika ingin mengganti file, pilih file baru di bawah ini.</small>
                            </div>
                        @endif
                        <input type="file" name="file_path" class="form-control @error('file_path') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png">
                        <div class="form-text">Format: PDF, DOC, DOCX, JPG, PNG. Maks: 5MB</div>
                        @error('file_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning"><i class="bi bi-pencil me-2"></i>Perbarui Progress</button>
                        <a href="{{ route('mahasiswa.bimbingan.show', $bimbingan) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
