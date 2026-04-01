@extends('layouts.app')
@section('title', 'Upload Berkas Ujian')
@section('page-header')@endsection
@section('page-title', 'Upload Berkas Ujian')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-upload me-2 text-primary"></i>Upload Dokumen Ujian</div>
    <div class="card-body">
        <div class="alert alert-info small">
            <i class="bi bi-info-circle me-1"></i>
            Upload berkas BAP (Berita Acara Pelaksanaan) dan berkas nilai setelah ujian <strong>{{ $ujian->jenisLabel }}</strong> terlaksana.
        </div>
        <form action="{{ route('mahasiswa.ujian.dokumen.store', $ujian) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Berkas BAP <span class="text-danger">*</span></label>
                <input type="file" name="berkas_bap" class="form-control @error('berkas_bap') is-invalid @enderror" accept=".pdf">
                <div class="form-text">Format: PDF, Maks: 10MB</div>
                @error('berkas_bap')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Berkas Nilai <small class="text-muted">(opsional)</small></label>
                <input type="file" name="berkas_nilai" class="form-control @error('berkas_nilai') is-invalid @enderror" accept=".pdf">
                <div class="form-text">Format: PDF. Maks: 10MB</div>
                @error('berkas_nilai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai</label>
                <input type="text" name="nilai" class="form-control @error('nilai') is-invalid @enderror" value="{{ old('nilai') }}" placeholder="cth: A, B+, 85">
                @error('nilai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload me-2"></i>Upload Berkas</button>
                <a href="{{ route('mahasiswa.ujian.show', $ujian) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
