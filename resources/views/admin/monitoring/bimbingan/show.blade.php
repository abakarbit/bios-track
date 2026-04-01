@extends('layouts.app')
@section('title', 'Detail Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Detail Bimbingan')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Bimbingan</div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th class="text-muted">Jenis</th><td><span class="badge {{ ['proposal'=>'bg-primary','seminar_hasil'=>'bg-success','laporan_skripsi'=>'bg-danger'][$bimbingan->jenis_bimbingan] ?? 'bg-secondary' }}">{{ $bimbingan->jenisLabel }}</span></td></tr>
                    @if($bimbingan->topik)<tr><th class="text-muted">Topik</th><td>{{ $bimbingan->topik }}</td></tr>@endif
                    <tr><th class="text-muted">Mahasiswa</th><td>{{ ucwords($bimbingan->mahasiswa->name) }}</td></tr>
                    <tr><th class="text-muted">NIM</th><td>{{ $bimbingan->mahasiswa->nim ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Pembimbing {{ $bimbingan->pembimbing }}</th><td>{{ ucwords($bimbingan->dosen->name) }}</td></tr>
                    <tr><th class="text-muted">Status</th><td>{!! $bimbingan->statusBadge !!}</td></tr>
                </table>
                @if($bimbingan->catatan_mahasiswa)
                <div class="alert alert-light mt-3 mb-0 p-2">
                    <strong><i class="bi bi-chat-text me-1 text-info"></i>Catatan Mahasiswa:</strong><br>
                    <small>{{ $bimbingan->catatan_mahasiswa }}</small>
                </div>
                @endif
                @if($bimbingan->catatan_dosen)
                <div class="alert alert-info mt-2 mb-0 p-2">
                    <strong><i class="bi bi-chat-quote me-1"></i>Catatan Dosen:</strong><br>
                    <small>{{ $bimbingan->catatan_dosen }}</small>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-shield-check me-2"></i>Status Admin</div>
            <div class="card-body">
                <div class="alert alert-info mb-0 p-2">
                    <small><i class="bi bi-info-circle me-1"></i>Anda hanya dapat melihat data ini. Approval dilakukan oleh Kaprodi/Dosen.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-list-ol me-2 text-success"></i>Progress Bimbingan ({{ $bimbingan->progresses->count() }})</span>
            </div>
            <div class="card-body p-0">
                @forelse($bimbingan->progresses->sortByDesc('created_at') as $p)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <small class="text-muted">{{ $p->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ $p->catatan }}</p>
                            @if($p->file_path)
                            <a href="{{ Storage::url($p->file_path) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem"><i class="bi bi-paperclip me-1"></i>Lampiran</a>
                            @endif
                            @if($p->catatan_dosen)
                            <div class="mt-2 p-2 rounded" style="background:#f0f8ff;border-left:3px solid #17a2b8">
                                <small><i class="bi bi-chat-quote me-1 text-info"></i><strong>Catatan Dosen:</strong> {{ $p->catatan_dosen }}</small>
                            </div>
                            @endif
                        </div>
                        <div class="ms-3 text-end">
                            {!! $p->statusBadge !!}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-text fs-3 d-block mb-2"></i>Belum ada progress.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
