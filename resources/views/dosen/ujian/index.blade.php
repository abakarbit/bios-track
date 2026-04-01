@extends('layouts.app')
@section('title', 'Daftar Ujian')
@section('page-header')@endsection
@section('page-title', 'Ujian yang Melibatkan Saya')

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Mahasiswa</th><th>Jenis Ujian</th><th>Tanggal</th><th>Tempat</th><th>Peran Saya</th><th>Status Saya</th><th>Status Ujian</th><th></th></tr>
                </thead>
                <tbody>
                @forelse($ujians as $ujian)
                @php
                    $myId = Auth::id();
                    $peran = 'Penguji';
                    $myStatus = 'menunggu';
                    if ($ujian->dosen_pembimbing1_id == $myId) { $peran = 'Pembimbing 1'; $myStatus = $ujian->status_pembimbing1; }
                    elseif ($ujian->dosen_pembimbing2_id == $myId) { $peran = 'Pembimbing 2'; $myStatus = $ujian->status_pembimbing2; }
                    elseif ($ujian->dosen_penguji1_id == $myId) { $peran = 'Penguji 1'; $myStatus = $ujian->status_penguji1; }
                    elseif ($ujian->dosen_penguji2_id == $myId) { $peran = 'Penguji 2'; $myStatus = $ujian->status_penguji2; }
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $ujian->mahasiswa->name }}</div>
                        <div class="small text-muted">{{ $ujian->mahasiswa->nim ?? '-' }}</div>
                    </td>
                    <td><span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$ujian->jenis_ujian] ?? 'secondary' }}">{{ $ujian->jenisLabel }}</span></td>
                    <td>{{ $ujian->tanggal_ujian->format('d M Y') }}</td>
                    <td>{{ $ujian->tempat_ujian }}</td>
                    <td><span class="badge bg-secondary">{{ $peran }}</span></td>
                    <td><span class="badge {{ $myStatus === 'disetujui' ? 'bg-success' : ($myStatus === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($myStatus) }}</span></td>
                    <td>{!! $ujian->statusBadge !!}</td>
                    <td>
                        <a href="{{ route('dosen.ujian.show', $ujian) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada ujian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ujians->hasPages())
    <div class="card-footer">{{ $ujians->links() }}</div>
    @endif
</div>
@endsection
