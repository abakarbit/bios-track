@extends('layouts.app')
@section('title', 'Data Ujian')
@section('page-header')@endsection
@section('page-title', 'Data Ujian')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                <option value="disetujui_dosen" {{ request('status')=='disetujui_dosen'?'selected':'' }}>Disetujui Dosen</option>
                <option value="disetujui_kaprodi" {{ request('status')=='disetujui_kaprodi'?'selected':'' }}>Disetujui Kaprodi</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
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
                <thead class="table-light"><tr><th>Mahasiswa</th><th>Jenis</th><th>Tanggal</th><th>Tempat</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($ujians as $ujian)
                <tr class="{{ $ujian->status === 'disetujui_dosen' ? 'table-warning' : '' }}">
                    <td><div class="fw-semibold">{{ $ujian->mahasiswa->name }}</div><div class="small text-muted">{{ $ujian->mahasiswa->nim ?? '-' }}</div></td>
                    <td><span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$ujian->jenis_ujian] ?? 'secondary' }}">{{ $ujian->jenisLabel }}</span></td>
                    <td class="small">{{ $ujian->tanggal_ujian->format('d M Y') }}</td>
                    <td class="small">{{ $ujian->tempat_ujian }}</td>
                    <td>{!! $ujian->statusBadge !!}</td>
                    <td><a href="{{ route('kaprodi.ujian.show', $ujian) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>{{ $ujian->status === 'disetujui_dosen' ? 'Review' : 'Detail' }}</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data ujian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ujians->hasPages())<div class="card-footer">{{ $ujians->withQueryString()->links() }}</div>@endif
</div>
@endsection
