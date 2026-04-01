@extends('layouts.app')
@section('title', 'Edit Jadwal Ujian')
@section('page-header')@endsection
@section('page-title', 'Edit Jadwal Ujian')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Catatan:</strong> Anda hanya bisa mengedit jadwal ujian yang belum disetujui semua dosen. Setelah ada dosen yang approve, tidak bisa diubah lagi.
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2 text-warning"></i>Form Edit Jadwal Ujian</div>
            <div class="card-body p-4">
                <form action="{{ route('mahasiswa.ujian.update', $ujian) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jenis Ujian <span class="text-danger">*</span></label>
                            <select name="jenis_ujian" class="form-select @error('jenis_ujian') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                <option value="proposal" {{ old('jenis_ujian', $ujian->jenis_ujian) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="seminar_hasil" {{ old('jenis_ujian', $ujian->jenis_ujian) == 'seminar_hasil' ? 'selected' : '' }}>Seminar Hasil</option>
                                <option value="laporan_skripsi" {{ old('jenis_ujian', $ujian->jenis_ujian) == 'laporan_skripsi' ? 'selected' : '' }}>Ujian Sidang Akhir</option>
                            </select>
                            @error('jenis_ujian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Ujian <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal_ujian" class="form-control @error('tanggal_ujian') is-invalid @enderror" value="{{ old('tanggal_ujian') }}" min="{{ date('Y-m-d') }}" required>
                            @error('tanggal_ujian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tempat Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_ujian" class="form-control @error('tempat_ujian') is-invalid @enderror" value="{{ old('tempat_ujian', $ujian->tempat_ujian) }}" placeholder="Contoh: Ruang Sidang Lantai 3" required>
                        @error('tempat_ujian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Pembimbing 1 <span class="text-danger">*</span></label>
                            <select name="dosen_pembimbing1_id" class="form-select @error('dosen_pembimbing1_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_pembimbing1_id', $ujian->dosen_pembimbing1_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('dosen_pembimbing1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Pembimbing 2</label>
                            <select name="dosen_pembimbing2_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_pembimbing2_id', $ujian->dosen_pembimbing2_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Penguji 1 <span class="text-danger">*</span></label>
                            <select name="dosen_penguji1_id" class="form-select @error('dosen_penguji1_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_penguji1_id', $ujian->dosen_penguji1_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('dosen_penguji1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Penguji 2</label>
                            <select name="dosen_penguji2_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_penguji2_id', $ujian->dosen_penguji2_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-warning"><i class="bi bi-pencil me-2"></i>Perbarui Jadwal Ujian</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
