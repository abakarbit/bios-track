@extends('layouts.app')
@section('title', 'Jadwal Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Jadwal Bimbingan Saya')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Bimbingan</li>
@endsection
@section('page-actions')
<a href="{{ route('mahasiswa.bimbingan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Dosen Pembimbing</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">#</th>
                        <th>Jenis Bimbingan</th>
                        <th>Dosen</th>
                        <th>Pembimbing</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bimbingans as $b)
                    <tr>
                        <td class="px-4 text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge {{ ['proposal'=>'bg-primary','seminar_hasil'=>'bg-success','laporan_skripsi'=>'bg-danger'][$b->jenis_bimbingan] ?? 'bg-secondary' }}">
                                {{ $b->jenisLabel }}
                            </span>
                        </td>
                        <td>{{ $b->dosen->name ?? '-' }}</td>
                        <td>{{ $b->pembimbing == 1 ? 'Pembimbing 1' : 'Pembimbing 2' }}</td>
                        <td>{!! $b->statusBadge !!}</td>
                        <td>
                            <small class="text-muted">{{ $b->progresses->count() }} progress
                                @if($b->progresses->where('status','menunggu')->count())
                                    <span class="badge bg-warning text-dark">{{ $b->progresses->where('status','menunggu')->count() }} pending</span>
                                @endif
                            </small>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('mahasiswa.bimbingan.show', $b) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></a>
                            @if($b->status === 'disetujui')
                            <a href="{{ route('mahasiswa.bimbingan.progress.create', $b) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-plus"></i> Progress</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-journal-x fs-2 d-block mb-2"></i>Belum ada jadwal bimbingan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
