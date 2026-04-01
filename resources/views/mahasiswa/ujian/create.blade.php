@extends('layouts.app')
@section('title', 'Buat Jadwal Ujian')
@section('page-header')@endsection
@section('page-title', 'Buat Jadwal Ujian')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark-plus me-2 text-success"></i>Form Pengajuan Jadwal Ujian</div>
            <div class="card-body p-4">
                <form action="{{ route('mahasiswa.ujian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jenis Ujian <span class="text-danger">*</span></label>
                            <select name="jenis_ujian" class="form-select @error('jenis_ujian') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($availableJenis as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis_ujian')==$jenis?'selected':'' }}>{{ \App\Models\Ujian::JENIS[$jenis] }}</option>
                                @endforeach
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
                        <input type="text" name="tempat_ujian" class="form-control @error('tempat_ujian') is-invalid @enderror" value="{{ old('tempat_ujian') }}" placeholder="Contoh: Ruang Sidang Lantai 3" required>
                        @error('tempat_ujian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Pembimbing 1 <span class="text-danger">*</span></label>
                            <select name="dosen_pembimbing1_id" class="form-select @error('dosen_pembimbing1_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($dosens as $d)<option value="{{ $d->id }}" {{ old('dosen_pembimbing1_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach
                            </select>
                            @error('dosen_pembimbing1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Pembimbing 2</label>
                            <select name="dosen_pembimbing2_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($dosens as $d)<option value="{{ $d->id }}" {{ old('dosen_pembimbing2_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Penguji 1 <span class="text-danger">*</span></label>
                            <select name="dosen_penguji1_id" class="form-select @error('dosen_penguji1_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($dosens as $d)<option value="{{ $d->id }}" {{ old('dosen_penguji1_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach
                            </select>
                            @error('dosen_penguji1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dosen Penguji 2</label>
                            <select name="dosen_penguji2_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($dosens as $d)<option value="{{ $d->id }}" {{ old('dosen_penguji2_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i>Ajukan Jadwal Ujian</button>
                        <a href="{{ route('mahasiswa.ujian.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
