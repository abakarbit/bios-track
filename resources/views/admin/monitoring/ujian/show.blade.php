@extends('layouts.app')
@section('title', 'Detail Ujian')
@section('page-header')@endsection
@section('page-title', 'Detail Ujian')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Ujian</div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th class="text-muted">Mahasiswa</th><td><div class="fw-semibold">{{ $ujian->mahasiswa->name }}</div><div class="tiny">{{ $ujian->mahasiswa->nim ?? '-' }}</div></td></tr>
                    <tr><th class="text-muted">Jenis</th><td><span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$ujian->jenis_ujian] ?? 'secondary' }}">{{ $ujian->jenisLabel }}</span></td></tr>
                    <tr><th class="text-muted">Tanggal</th><td>{{ $ujian->tanggal_ujian->format('d M Y, H:i') }} WIB</td></tr>
                    <tr><th class="text-muted">Tempat</th><td>{{ $ujian->tempat_ujian }}</td></tr>
                    <tr><th class="text-muted">Status</th><td>{!! $ujian->statusBadge !!}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-people me-2"></i>Tim Ujian</div>
            <div class="card-body small">
                <div class="mb-2">
                    <span class="badge bg-light text-dark mb-1">Pembimbing 1</span>
                    <div>{{ $ujian->pembimbing1->name ?? '-' }}</div>
                    <small class="text-muted">{!! ($ujian->status_pembimbing1 ? '<span class="badge bg-success" style="font-size:0.7rem">'.ucfirst($ujian->status_pembimbing1).'</span>' : '<span class="text-danger">Belum</span>') !!}</small>
                </div>
                <div class="mb-2">
                    <span class="badge bg-light text-dark mb-1">Penguji 1</span>
                    <div>{{ $ujian->penguji1->name ?? '-' }}</div>
                    <small class="text-muted">{!! ($ujian->status_penguji1 ? '<span class="badge bg-success" style="font-size:0.7rem">'.ucfirst($ujian->status_penguji1).'</span>' : '<span class="text-danger">Belum</span>') !!}</small>
                </div>
                <div class="mb-2">
                    <span class="badge bg-light text-dark mb-1">Penguji 2</span>
                    <div>{{ $ujian->penguji2->name ?? '-' }}</div>
                    <small class="text-muted">{!! ($ujian->status_penguji2 ? '<span class="badge bg-success" style="font-size:0.7rem">'.ucfirst($ujian->status_penguji2).'</span>' : '<span class="text-danger">Belum</span>') !!}</small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-shield-check me-2"></i>Status Admin</div>
            <div class="card-body">
                <div class="alert alert-info mb-0 p-2">
                    <small><i class="bi bi-info-circle me-1"></i>Anda hanya dapat melihat data ini. Approval dilakukan oleh Dosen/Kaprodi.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark me-2"></i>Dokumen Ujian</div>
            <div class="card-body">
                @if($ujian->dokumen)
                <table class="table table-sm mb-0">
                    <tr><th class="text-muted">Nilai</th><td class="fw-semibold">{{ $ujian->dokumen->nilai ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Catatan</th><td>{{ $ujian->dokumen->catatan ?? '-' }}</td></tr>
                    @if($ujian->dokumen->file_path)
                    <tr><th class="text-muted">File</th><td><a href="{{ Storage::url($ujian->dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download me-1"></i>Unduh</a></td></tr>
                    @endif
                </table>
                @else
                <div class="text-muted text-center py-3">
                    <i class="bi bi-file-earmark-x"></i> Belum ada dokumen.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
