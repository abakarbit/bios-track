@extends('layouts.app')
@section('title', 'Tentukan Dosen Pembimbing')
@section('page-header')@endsection
@section('page-title', 'Tentukan Dosen Pembimbing')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.bimbingan.index') }}">Dosen Pembimbing</a></li>
<li class="breadcrumb-item active">Tentukan Dosen Pembimbing</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-plus me-2 text-primary"></i>Form Pengajuan Dosen Pembimbing</div>
            <div class="card-body p-4">
                <form action="{{ route('mahasiswa.bimbingan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Bimbingan <span class="text-danger">*</span></label>
                        <select name="jenis_bimbingan" class="form-select @error('jenis_bimbingan') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Bimbingan --</option>
                            @foreach($availableJenis as $jenis)
                            <option value="{{ $jenis }}" {{ old('jenis_bimbingan')==$jenis?'selected':'' }}>
                                {{ \App\Models\Bimbingan::JENIS[$jenis] }}
                            </option>
                            @endforeach
                        </select>
                        @error('jenis_bimbingan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Jenis bimbingan yang tersedia disesuaikan dengan tahap akademik Anda saat ini.</small>
                    </div>
                    <!-- Topik -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Topik Bimbingan <span class="text-danger">*</span></label>
                        <input type="text" name="topik" class="form-control @error('topik') is-invalid @enderror" value="{{ old('topik') }}" required>
                        @error('topik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Tuliskan topik atau judul sementara untuk bimbingan ini.</small>
                    </div>

                    {{-- <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Bimbingan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_bimbingan" class="form-control @error('tanggal_bimbingan') is-invalid @enderror" value="{{ old('tanggal_bimbingan') }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_bimbingan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div> --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dosen Pembimbing <span class="text-danger">*</span></label>
                        <select name="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ old('dosen_id')==$d->id?'selected':'' }}>{{ $d->name }} @if($d->nip)(NIP: {{ $d->nip }})@endif</option>
                            @endforeach
                        </select>
                        @error('dosen_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <input type="radio" name="pembimbing" id="pembimbing1" value="1" {{ old('pembimbing')=='1' ? 'checked' : '' }} required>
                        <label for="pembimbing1" class="form-label fw-semibold">Pembimbing 1</label> &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="pembimbing" id="pembimbing2" value="2" {{ old('pembimbing')=='2' ? 'checked' : '' }} required>
                        <label for="pembimbing2" class="form-label fw-semibold">Pembimbing 2</label>
                        @error('pembimbing')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Catatan / Agenda Bimbingan</label>
                        <textarea name="catatan_mahasiswa" class="form-control" rows="3" placeholder="Tuliskan agenda atau catatan untuk bimbingan ini..." maxlength="500">{{ old('catatan_mahasiswa') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Ajukan Jadwal</button>
                        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
