@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')
@section('page-header')@endsection
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('page-actions')

@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #1a3c6e, #2a9d8f); border-radius:16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="text-white fw-bold mb-1">Selamat datang, {{ Auth::user()->name }}! 👋</h4>
                    <p class="text-white-50 mb-0">NIM: {{ Auth::user()->nim }} | Prodi: {{ Auth::user()->prodi }} | Angkatan
                        {{ Auth::user()->angkatan }}</p>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        @php
                            $stages = [
                                'proposal' => ['label' => 'Proposal', 'icon' => 'bi-1-circle'],
                                'seminar_hasil' => ['label' => 'Seminar Hasil', 'icon' => 'bi-2-circle'],
                                'laporan_skripsi' => ['label' => 'Ujian Sidang Akhir', 'icon' => 'bi-3-circle'],
                            ];
                            $stageOrder = ['proposal', 'seminar_hasil', 'laporan_skripsi'];
                        @endphp
                        @foreach ($stageOrder as $idx => $s)
                            <span
                                class="badge px-3 py-2 {{ $currentStage === $s ? 'bg-warning text-dark' : ($stageOrder[array_search($currentStage, $stageOrder)] > $idx ? 'bg-success' : 'bg-secondary') }}"
                                style="font-size:12px">
                                <i class="bi {{ $stages[$s]['icon'] }} me-1"></i>{{ $stages[$s]['label'] }}
                                @if ($stageOrder[array_search($currentStage, $stageOrder)] > $idx)
                                    <i class="bi bi-check ms-1"></i>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="text-center" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px 24px">
                    <div class="text-warning fw-bold" style="font-size:2rem">
                        {{ strtoupper(Str::limit(str_replace(['proposal', 'seminar_hasil', 'laporan_skripsi', 'selesai'], ['P', 'SH', 'LS', '✓'], $currentStage), 5)) }}
                    </div>
                    <div class="text-white-50 small">Tahap Saat Ini</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="card h-100 border-0" style="background:linear-gradient(135deg,#667eea,#764ba2)">
                <div class="card-body text-white p-3 d-flex align-items-center gap-3">
                    <i class="bi bi-journal-bookmark-fill" style="font-size:2rem;opacity:0.8"></i>
                    <div>
                        <div style="font-size:1.8rem;font-weight:700">{{ $stats['bimbingan_total'] }}</div>
                        <div class="small opacity-75">Total Pembimbing</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card h-100 border-0" style="background:linear-gradient(135deg,#11998e,#38ef7d)">
                <div class="card-body text-white p-3 d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill" style="font-size:2rem;opacity:0.8"></i>
                    <div>
                        <div style="font-size:1.8rem;font-weight:700">{{ $stats['bimbingan_disetujui'] }}</div>
                        <div class="small opacity-75">Disetujui</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card h-100 border-0" style="background:linear-gradient(135deg,#f7971e,#ffd200)">
                <div class="card-body text-white p-3 d-flex align-items-center gap-3">
                    <i class="bi bi-hourglass-split" style="font-size:2rem;opacity:0.8"></i>
                    <div>
                        <div style="font-size:1.8rem;font-weight:700">{{ $stats['bimbingan_menunggu'] }}</div>
                        <div class="small opacity-75">Menunggu</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- menunggu paraf dosen -->
        <div class="col-6 col-md-2">
            <div class="card h-100 border-0" style="background:linear-gradient(135deg,#ee9ca7,#ffdde1)">
                <div class="card-body text-white p-3 d-flex align-items-center gap-3">
                    <i class="bi bi-pen-fill" style="font-size:2rem;opacity:0.8"></i>
                    <div>
                        <div style="font-size:1.8rem;font-weight:700">{{ $stats['progress_menunggu_paraf'] }}</div>
                        <div class="small opacity-75">Menunggu Paraf</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="card h-100 border-0" style="background:linear-gradient(135deg,#ee0979,#ff6a00)">
                <div class="card-body text-white p-3 d-flex align-items-center gap-3">
                    <i class="bi bi-file-earmark-check-fill" style="font-size:2rem;opacity:0.8"></i>
                    <div>
                        <div style="font-size:1.8rem;font-weight:700">
                            {{ $stats['ujian_selesai'] }}/{{ $stats['ujian_total'] }}</div>
                        <div class="small opacity-75">Ujian Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Bimbingan -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-journal-text me-2 text-primary"></i>Bimbingan Terbaru</span>

                    <div class=" text-end">
                        <a href="{{ route('mahasiswa.export-pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="bi bi-file-pdf me-1"></i> Export PDF
                        </a>
                        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="btn btn-sm btn-outline-primary">Lihat
                            Semua</a>
                    </div>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">#</th>
                                <th>Tanggal Bimbingan</th>
                                <th>Tahapan Bimbingan</th>
                                <th>Dosen Pembimbing</th>
                                <th>Status Paraf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($progressRecords as $p)
                                <tr>
                                    <td class="px-4 text-muted small">{{ $loop->iteration }}</td>
                                    <td><small>{{ $p->tanggal_bimbingan ? $p->tanggal_bimbingan->format('d M Y H:i') : '-' }}</small></td>
                                    <td><small>{{ Str::limit($p->catatan, 50) }}</small></td>
                                    <td><small>{{ $p->bimbingan->dosen->name ?? '-' }}</small></td>
                                    <td>
                                        @if ($p->status === 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu Paraf</span>
                                        @elseif($p->status === 'disetujui')
                                            <span class="badge bg-success">Sudah Diparaf</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada progress bimbingan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications & Quick Actions -->
        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-bell me-2 text-warning"></i>Notifikasi Terbaru</span>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $n)
                        <form action="{{ route('notifications.markRead', $n->id) }}" method="POST" class="text-decoration-none" style="display:contents;">
                            @csrf
                            <button type="submit" class="d-flex gap-3 px-3 py-2 border-bottom w-100 text-decoration-none"
                                style="background:{{ $n->is_read ? 'transparent' : '#fffbf0' }}; border:none; cursor:pointer; text-align:left; padding:0.75rem 0.75rem;">
                                <i class="bi {{ ['success' => 'bi-check-circle-fill text-success', 'danger' => 'bi-x-circle-fill text-danger', 'warning' => 'bi-exclamation-circle-fill text-warning', 'info' => 'bi-info-circle-fill text-info'][$n->type] ?? 'bi-bell' }} mt-1"
                                    style="flex-shrink:0"></i>
                                <div>
                                    <div class="small fw-semibold text-dark">{{ $n->title }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ Str::limit($n->message, 60) }}</div>
                                    <div class="text-muted" style="font-size:10px">{{ $n->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </button>
                        </form>
                    @empty
                        <div class="text-center py-3 text-muted small">Tidak ada notifikasi baru</div>
                    @endforelse
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat</div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('mahasiswa.bimbingan.create') }}" class="btn btn-outline-primary"><i
                            class="bi bi-plus-circle me-2"></i>Dosen Pembimbing</a>
                    <a href="{{ route('mahasiswa.ujian.create') }}" class="btn btn-outline-success"><i
                            class="bi bi-plus-circle me-2"></i>Jadwal Ujian</a>
                    <a href="{{ route('mahasiswa.bimbingan.riwayat') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-clock-history me-2"></i>Riwayat Bimbingan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
