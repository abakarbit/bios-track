@extends('layouts.app')
@section('title', 'Dashboard Dosen')
@section('page-header')@endsection
@section('page-title', 'Dashboard Dosen')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-primary">{{ $totalMahasiswa }}</div>
                <div class="small text-muted mt-1">Mahasiswa Bimbingan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning">{{ $pendingBimbingan }}</div>
                <div class="small text-muted mt-1">Bimbingan Menunggu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-info">{{ $pendingProgress }}</div>
                <div class="small text-muted mt-1">Progress Perlu Paraf</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-danger">{{ $pendingUjian }}</div>
                <div class="small text-muted mt-1">Ujian Perlu Disetujui</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-success">{{ $bimbinganSelesai }}</div>
                <div class="small text-muted mt-1">Bimbingan Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-secondary">{{ $bimbinganBelumSelesai }}</div>
                <div class="small text-muted mt-1">Bimbingan Belum Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2 text-warning"></i>Bimbingan Menunggu Persetujuan</span>
                <a href="{{ route('dosen.bimbingan.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentPendingBimbingan as $bimbingan)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ ucwords($bimbingan->mahasiswa->name) }}</div>
                        <div class="small text-muted">{{ $bimbingan->jenisLabel }}</div>
                    </div>
                    <a href="{{ route('dosen.bimbingan.show', $bimbingan) }}" class="btn btn-sm btn-outline-primary">Review</a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Tidak ada bimbingan menunggu.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pen me-2 text-info"></i>Progress Perlu Paraf</span>
                <a href="{{ route('dosen.bimbingan.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentPendingProgress as $progress)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ ucwords($progress->bimbingan->mahasiswa->name) }}</div>
                        <div class="small text-muted">{{ Str::limit($progress->catatan, 60) }}</div>
                    </div>
                    <a href="{{ route('dosen.bimbingan.show', $progress->bimbingan) }}" class="btn btn-sm btn-outline-info">Paraf</a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Tidak ada progress menunggu paraf.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-2 text-danger"></i>Ujian Menunggu Persetujuan</span>
                <a href="{{ route('dosen.ujian.index') }}" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentPendingUjian as $ujian)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ ucwords($ujian->mahasiswa->name) }} &mdash; {{ $ujian->jenisLabel }}</div>
                        <div class="small text-muted">{{ $ujian->tanggal_ujian->format('d M Y') }} di {{ $ujian->tempat_ujian }}</div>
                    </div>
                    <a href="{{ route('dosen.ujian.show', $ujian) }}" class="btn btn-sm btn-outline-danger">Review</a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Tidak ada ujian menunggu persetujuan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
