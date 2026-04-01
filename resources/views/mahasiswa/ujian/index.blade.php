@extends('layouts.app')
@section('title', 'Jadwal Ujian')
@section('page-header')@endsection
@section('page-title', 'Jadwal Ujian Saya')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Ujian</li>
@endsection
@section('page-actions')
<a href="{{ route('mahasiswa.ujian.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i> Buat Jadwal Ujian</a>
@endsection

@section('content')
<div class="row g-4">
    @forelse($ujians as $u)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="badge {{ ['proposal'=>'bg-primary','seminar_hasil'=>'bg-success','laporan_skripsi'=>'bg-danger'][$u->jenis_ujian] ?? 'bg-secondary' }} fs-6">
                    {{ $u->jenisLabel }}
                </span>
                {!! $u->statusBadge !!}
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex gap-2 mb-2">
                        <i class="bi bi-calendar3 text-primary"></i>
                        <span class="small">{{ $u->tanggal_ujian->format('d M Y H:i') }}</span>
                    </div>
                    <div class="d-flex gap-2 mb-2">
                        <i class="bi bi-geo-alt text-danger"></i>
                        <span class="small">{{ $u->tempat_ujian }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted fw-semibold d-block mb-1">Panel Penguji:</small>
                    <div class="small"><i class="bi bi-person-fill text-success me-1"></i>Pembimbing 1: {{ $u->pembimbing1->name ?? '-' }}</div>
                    @if($u->pembimbing2)<div class="small"><i class="bi bi-person-fill text-success me-1"></i>Pembimbing 2: {{ $u->pembimbing2->name }}</div>@endif
                    <div class="small mt-1"><i class="bi bi-person-fill text-warning me-1"></i>Penguji 1: {{ $u->penguji1->name ?? '-' }}</div>
                    @if($u->penguji2)<div class="small"><i class="bi bi-person-fill text-warning me-1"></i>Penguji 2: {{ $u->penguji2->name }}</div>@endif
                </div>
                <!-- Approval Status -->
                <div class="border rounded p-2" style="background:#f8f9fa">
                    <small class="text-muted fw-semibold">Status Persetujuan:</small>
                    <div class="mt-1 d-flex flex-wrap gap-1">
                        <span class="badge {{ $u->status_pembimbing1 === 'disetujui' ? 'bg-success' : ($u->status_pembimbing1 === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">Pemb.1</span>
                        @if($u->dosen_pembimbing2_id)<span class="badge {{ $u->status_pembimbing2 === 'disetujui' ? 'bg-success' : ($u->status_pembimbing2 === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">Pemb.2</span>@endif
                        <span class="badge {{ $u->status_kaprodi === 'disetujui' ? 'bg-success' : ($u->status_kaprodi === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">Kaprodi</span>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                @if($u->status === 'menunggu' && $u->mahasiswa_id == Auth::id())
                <a href="{{ route('mahasiswa.ujian.edit', $u) }}" class="btn btn-sm btn-warning flex-fill">
                    <i class="bi bi-pencil-square me-1"></i>Edit
                </a>
                @endif
                @if($u->status === 'disetujui_kaprodi' && !$u->dokumen)
                <a href="{{ route('mahasiswa.ujian.dokumen.create', $u) }}" class="btn btn-sm btn-success flex-fill">
                    <i class="bi bi-upload me-1"></i>Upload BAP
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="card"><div class="card-body text-center py-5 text-muted"><i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>Belum ada jadwal ujian</div></div></div>
    @endforelse
</div>
@endsection
