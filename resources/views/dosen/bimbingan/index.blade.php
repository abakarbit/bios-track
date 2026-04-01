@extends('layouts.app')
@section('title', 'Daftar Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Daftar Bimbingan Mahasiswa')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                    <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="proposal" {{ request('jenis')=='proposal'?'selected':'' }}>Proposal</option>
                    <option value="seminar_hasil" {{ request('jenis')=='seminar_hasil'?'selected':'' }}>Seminar Hasil</option>
                    <option value="laporan_skripsi" {{ request('jenis')=='laporan_skripsi'?'selected':'' }}>Ujian Sidang Akhir</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Cari Mahasiswa</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama mahasiswa..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Mahasiswa</th><th>Jenis</th><th>Topik</th><th>Progress</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                @forelse($bimbingans as $bimbingan)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $bimbingan->mahasiswa->name }}</div>
                        <div class="small text-muted">{{ $bimbingan->mahasiswa->nim ?? '-' }}</div>
                    </td>
                    <td><span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$bimbingan->jenis_bimbingan] ?? 'secondary' }}">{{ $bimbingan->jenisLabel }}</span></td>
                    <td class="small">{{ Str::limit($bimbingan->topik, 50) }}</td>
                    <td>
                        @php $pendingProgress = $bimbingan->progresses->where('status','menunggu')->count(); @endphp
                        @if($pendingProgress > 0)
                        <span class="badge bg-warning text-dark">{{ $pendingProgress }} menunggu paraf</span>
                        @else
                        <span class="text-muted small">{{ $bimbingan->progresses->count() }} catatan</span>
                        @endif
                    </td>
                    <td>{!! $bimbingan->statusBadge !!}</td>
                    <td><a href="{{ route('dosen.bimbingan.show', $bimbingan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data bimbingan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bimbingans->hasPages())
    <div class="card-footer">{{ $bimbingans->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
