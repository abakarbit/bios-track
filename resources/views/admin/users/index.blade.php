@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-header')@endsection
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Pengguna
            </a>
        </div>
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ request('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="kaprodi" {{ request('role') === 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Nama, email, NIM, NIP..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>NIM/NIP</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <small class="text-muted">{{ $user->prodi ?? '-' }}</small>
                    </td>
                    <td class="small">{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-{{ $user->role === 'mahasiswa' ? 'info' : ($user->role === 'dosen' ? 'success' : ($user->role === 'kaprodi' ? 'warning' : 'secondary')) }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="small">{{ $user->nim ?? $user->nip ?? '-' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        onclick="return confirm('Yakin?')">
                                    <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Hapus"
                                        onclick="return confirm('Yakin hapus pengguna ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada pengguna.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">{{ $users->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
