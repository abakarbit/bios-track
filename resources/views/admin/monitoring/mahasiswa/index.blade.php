@extends('layouts.app')
@section('title', 'Data Mahasiswa')
@section('page-header')@endsection
@section('page-title', 'Data Mahasiswa')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau NIM..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="prodi" class="form-control form-control-sm" placeholder="Program studi..." value="{{ request('prodi') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="angkatan" class="form-control form-control-sm" placeholder="Angkatan..." value="{{ request('angkatan') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Nama</th><th>NIM</th><th>Prodi</th><th>Angkatan</th><th>Bimbingan</th><th>Ujian</th><th></th></tr>
                </thead>
                <tbody>
                @forelse($mahasiswas as $mhs)
                <tr>
                    <td><div class="fw-semibold">{{ $mhs->name }}</div><div class="small text-muted">{{ $mhs->email }}</div></td>
                    <td>{{ $mhs->nim ?? '-' }}</td>
                    <td>{{ $mhs->prodi ?? '-' }}</td>
                    <td>{{ $mhs->angkatan ?? '-' }}</td>
                    <td><span class="badge bg-secondary">{{ $mhs->bimbingans_count ?? 0 }} sesi</span></td>
                    <td><span class="badge bg-secondary">{{ $mhs->ujians_count ?? 0 }} jadwal</span></td>
                    <td><a href="{{ route('admin.mahasiswa.detail', $mhs) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data mahasiswa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mahasiswas->hasPages())<div class="card-footer">{{ $mahasiswas->withQueryString()->links() }}</div>@endif
</div>
@endsection
