@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-header')@endsection
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Total Pengguna</div>
                        <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                    <i class="bi bi-people-fill text-primary" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Mahasiswa</div>
                        <h3 class="mb-0">{{ $stats['mahasiswa'] }}</h3>
                    </div>
                    <i class="bi bi-mortarboard-fill text-success" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Dosen</div>
                        <h3 class="mb-0">{{ $stats['dosen'] }}</h3>
                    </div>
                    <i class="bi bi-person-badge-fill text-info" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Kaprodi</div>
                        <h3 class="mb-0">{{ $stats['kaprodi'] }}</h3>
                    </div>
                    <i class="bi bi-shield-lock-fill text-warning" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Admin</div>
                        <h3 class="mb-0">{{ $stats['admin'] }}</h3>
                    </div>
                    <i class="bi bi-lock-fill text-danger" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Pengguna Aktif</div>
                        <h3 class="mb-0">{{ $stats['active_users'] }}</h3>
                    </div>
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Manajemen Admin</h5>
    </div>
    <div class="card-body">
        <div class="list-group">
            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action py-3">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><i class="bi bi-people me-2"></i>Kelola Pengguna</h6>
                        <small class="text-muted">Lihat, buat, edit, dan hapus pengguna</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
