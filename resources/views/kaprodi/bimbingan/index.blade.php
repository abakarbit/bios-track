@extends('layouts.app')
@section('title', 'Data Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Data Bimbingan')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                @foreach(['menunggu','disetujui','ditolak','selesai'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select></div>
            <div class="col-md-3"><select name="jenis" class="form-select form-select-sm">
                <option value="">Semua Jenis</option>
                <option value="proposal" {{ request('jenis')=='proposal'?'selected':'' }}>Proposal</option>
                <option value="seminar_hasil" {{ request('jenis')=='seminar_hasil'?'selected':'' }}>Seminar Hasil</option>
                <option value="laporan_skripsi" {{ request('jenis')=='laporan_skripsi'?'selected':'' }}>Ujian Sidang Akhir</option>
            </select></div>
            <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari mahasiswa..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>No</th><th>Jenis Bimbingan</th><th>Tanggal</th><th>Tahapan Bimbingan</th><th>Dosen Pembimbing</th><th>Status Paraf</th></tr></thead>
                <tbody>
                @forelse($progresses as $i => $progress)
                <tr>
                    <td class="text-muted small">{{ $progresses->firstItem() + $i }}</td>
                    <td>
                        <span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$progress->bimbingan->jenis_bimbingan] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $progress->bimbingan->jenis_bimbingan)) }}
                        </span>
                        <div class="small text-muted">{{ $progress->bimbingan->mahasiswa->name }}</div>
                        <div class="small text-muted">{{ $progress->bimbingan->mahasiswa->nim ?? '-' }}</div>
                    </td>
                    <td class="small">
                        <div>{{ $progress->created_at->format('d M Y') }}</div>
                        <div class="text-muted">{{ $progress->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="small">
                        <div class="text-truncate" title="{{ $progress->catatan }}">
                            {{ Str::limit($progress->catatan, 50) }}
                        </div>
                    </td>
                    <td class="small">
                        {{ $progress->bimbingan->dosen->name ?? '-' }}
                        @if($progress->bimbingan->pembimbing == 1)
                            <span class="badge bg-success">P1</span>
                        @elseif($progress->bimbingan->pembimbing == 2)
                            <span class="badge bg-info">P2</span>
                        @endif
                    </td>
                    <td>
                        @if($progress->status == 'menunggu')
                            <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu</span>
                        @elseif($progress->status == 'disetujui')
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                        @elseif($progress->status == 'ditolak')
                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                        @elseif($progress->status == 'selesai')
                            <span class="badge bg-secondary"><i class="bi bi-check-square"></i> Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data bimbingan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($progresses->hasPages())<div class="card-footer">{{ $progresses->withQueryString()->links() }}</div>@endif
</div>
@endsection
