@extends('layouts.app')
@section('title', 'Dashboard Kaprodi')
@section('page-header')@endsection
@section('page-title', 'Dashboard Kaprodi')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-primary">{{ $totalMahasiswa }}</div>
                <div class="small text-muted mt-1">Total Mahasiswa</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-success">{{ $totalDosen }}</div>
                <div class="small text-muted mt-1">Total Dosen</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning">{{ $pendingUjian }}</div>
                <div class="small text-muted mt-1">Ujian Perlu Disetujui</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="display-6 fw-bold text-danger">{{ $ujianSelesai }}</div>
                <div class="small text-muted mt-1">Ujian Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-hourglass me-2 text-warning"></i>Ujian Menunggu Persetujuan Kaprodi</span>
                <a href="{{ route('kaprodi.ujian.index', ['status'=>'disetujui_dosen']) }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($pendingUjianList as $ujian)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $ujian->mahasiswa->name }} &mdash; <span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$ujian->jenis_ujian] ?? 'secondary' }}">{{ $ujian->jenisLabel }}</span></div>
                        <div class="small text-muted">{{ $ujian->tanggal_ujian->format('d M Y') }} di {{ $ujian->tempat_ujian }}</div>
                    </div>
                    <a href="{{ route('kaprodi.ujian.show', $ujian) }}" class="btn btn-sm btn-warning"><i class="bi bi-check2 me-1"></i>Review</a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Tidak ada ujian menunggu persetujuan.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2 text-primary"></i>Mahasiswa Aktif</span>
                <a href="{{ route('kaprodi.mahasiswa.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentMahasiswa as $mhs)
                <div class="d-flex align-items-center p-3 border-bottom">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $mhs->name }}</div>
                        <div class="small text-muted">{{ $mhs->nim ?? 'NIM belum diisi' }} &middot; {{ $mhs->prodi ?? '-' }}</div>
                    </div>
                    <a href="{{ route('kaprodi.mahasiswa.detail', $mhs) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Belum ada mahasiswa.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
