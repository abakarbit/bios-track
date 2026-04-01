@extends('layouts.app')
@section('title', 'Detail Mahasiswa')
@section('page-header')@endsection
@section('page-title', 'Detail Mahasiswa: '.$mahasiswa->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white mb-3" style="width:80px;height:80px;font-size:2rem">
                    {{ strtoupper(substr($mahasiswa->name,0,1)) }}
                </div>
                <h5>{{ $mahasiswa->name }}</h5>
                <p class="text-muted small">{{ $mahasiswa->email }}</p>
                <table class="table table-borderless table-sm text-start small">
                    <tr><th>NIM</th><td>{{ $mahasiswa->nim ?? '-' }}</td></tr>
                    <tr><th>Prodi</th><td>{{ $mahasiswa->prodi ?? '-' }}</td></tr>
                    <tr><th>Angkatan</th><td>{{ $mahasiswa->angkatan ?? '-' }}</td></tr>
                    <tr><th>No. HP</th><td>{{ $mahasiswa->phone ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-bar-chart me-2"></i>Statistik</div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2"><span>Total Bimbingan</span><strong>{{ $bimbingans->pluck('jenis_bimbingan')->unique()->count() }}</strong></div>
                <div class="d-flex justify-content-between border-bottom py-2"><span>Bimbingan Selesai</span><strong>{{ $bimbingans->groupBy('jenis_bimbingan')->filter(function($group) { return $group->every(function($item) { return $item->status === 'selesai'; }); })->count() }}</strong></div>
                <div class="d-flex justify-content-between border-bottom py-2"><span>Total Ujian</span><strong>{{ $ujians->count() }}</strong></div>
                <div class="d-flex justify-content-between py-2"><span>Ujian Selesai</span><strong>{{ $ujians->where('status','selesai')->count() }}</strong></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-book me-2 text-primary"></i>Riwayat Bimbingan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px; text-align: center;">No</th>
                                <th style="min-width: 120px;">Jenis Bimbingan</th>
                                <th style="width: 100px;">Tanggal</th>
                                <th style="min-width: 200px;">Tahapan Bimbingan</th>
                                <th style="min-width: 150px;">Dosen Pembimbing</th>
                                <th style="width: 100px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($bimbingans as $index => $bimbingan)
                        <tr>
                            <td class="text-muted small text-center">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$bimbingan->jenis_bimbingan] ?? 'secondary' }}" style="font-size: 0.75rem; padding: 4px 8px;">
                                    {{ $bimbingan->jenisLabel }}
                                </span>
                            </td>
                            <td class="small">
                                <small class="d-block">{{ $bimbingan->created_at->format('d M Y') }}</small>
                                <small class="text-muted">{{ $bimbingan->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="small">
                                @if($bimbingan->topik)
                                    <strong>{{ Str::limit($bimbingan->topik, 50) }}</strong>
                                @endif
                                <small class="d-block text-muted">
                                    <i class="bi bi-journal-text" style="font-size: 0.65rem;"></i>
                                    {{ $bimbingan->progresses_count ?? $bimbingan->progresses->count() }} catatan progress
                                </small>
                            </td>
                            <td class="small">
                                <small class="d-flex align-items-center gap-1 mb-1">
                                    <i class="bi bi-person-fill text-muted" style="font-size: 0.65rem;"></i>
                                    {{ Str::limit($bimbingan->dosen->name ?? '-', 30) }}
                                </small>
                                <small>
                                    <span class="badge bg-light text-muted" style="font-size: 0.65rem; padding: 2px 6px;">
                                        <i class="bi bi-person-badge"></i> Pembimbing {{ $bimbingan->pembimbing }}
                                    </span>
                                </small>
                            </td>
                            <td class="text-center">{!! $bimbingan->statusBadge !!}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small">
                                <i class="bi bi-journal-x d-block mb-2" style="font-size: 1.2rem;"></i>
                                Belum ada bimbingan
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar-event me-2 text-danger"></i>Riwayat Ujian</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light"><tr><th>Jenis</th><th>Tanggal</th><th>Tempat</th><th>Nilai</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($ujians as $ujian)
                        <tr>
                            <td><span class="badge bg-{{ ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'][$ujian->jenis_ujian] ?? 'secondary' }} small">{{ $ujian->jenisLabel }}</span></td>
                            <td class="small">{{ $ujian->tanggal_ujian->format('d M Y') }}</td>
                            <td class="small">{{ $ujian->tempat_ujian }}</td>
                            <td class="small">{{ $ujian->dokumen->nilai ?? '-' }}</td>
                            <td>{!! $ujian->statusBadge !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted small">Belum ada ujian.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
