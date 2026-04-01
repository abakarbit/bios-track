@extends('layouts.app')
@section('title', 'Riwayat Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Riwayat Bimbingan')

@section('content')
<style>
    .filter-bar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px 20px;
    }
    .filter-bar .filter-select {
        flex: 1;
        min-width: 200px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 7px 32px 7px 12px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #374151;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m4 6 4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 16px;
        appearance: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .filter-bar .filter-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        outline: none;
    }
    .btn-export-pdf {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #fff;
        background: #dc2626;
        border: none;
        border-radius: 8px;
        transition: all 0.15s;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-export-pdf:hover {
        background: #b91c1c;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        text-decoration: none;
    }
    @media (max-width: 576px) {
        .btn-export-pdf {
            padding: 6px 10px;
            font-size: 0.75rem;
        }
        .btn-export-pdf span {
            display: none !important;
        }
    }
    .filter-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        color: #6b7280;
        margin-top: 10px;
    }
    .filter-label .filter-active {
        background: #eef2ff;
        color: #4338ca;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
    }

    /* Table */
    .data-table-wrapper {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .data-table {
        width: 100%;
        margin: 0;
    }
    .data-table thead th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #6b7280;
        white-space: nowrap;
    }
    .data-table tbody td {
        padding: 14px 16px;
        font-size: 0.85rem;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    .data-table tbody tr {
        transition: background-color 0.1s;
    }
    .data-table tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Badge Jenis */
    .badge-jenis {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        white-space: nowrap;
    }
    .badge-jenis.type-proposal { background: #eef2ff; color: #4338ca; }
    .badge-jenis.type-seminar_hasil { background: #ecfdf5; color: #065f46; }
    .badge-jenis.type-laporan_skripsi { background: #fef2f2; color: #991b1b; }
    .badge-jenis i { font-size: 0.7rem; }

    /* Badge Status */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.73rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-status.status-menunggu { background: #fffbeb; color: #92400e; }
    .badge-status.status-disetujui { background: #ecfdf5; color: #065f46; }
    .badge-status.status-ditolak { background: #fef2f2; color: #991b1b; }
    .badge-status .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-status.status-menunggu .status-dot { background: #f59e0b; animation: pulse-dot 2s infinite; }
    .badge-status.status-disetujui .status-dot { background: #10b981; }
    .badge-status.status-ditolak .status-dot { background: #ef4444; }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* Cell styles */
    .cell-no {
        font-size: 0.78rem;
        font-weight: 700;
        color: #d1d5db;
        text-align: center;
        width: 40px;
    }
    .cell-date {
        font-size: 0.84rem;
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
    }
    .cell-date small {
        display: block;
        font-size: 0.72rem;
        font-weight: 400;
        color: #9ca3af;
        margin-top: 1px;
    }
    .cell-catatan {
        font-size: 0.83rem;
        color: #4b5563;
        line-height: 1.45;
        max-width: 220px;
    }
    .cell-dosen {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .cell-dosen-avatar {
        width: 30px;
        height: 30px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #6b7280;
        flex-shrink: 0;
    }
    .cell-dosen-name {
        font-size: 0.83rem;
        font-weight: 600;
        color: #374151;
        line-height: 1.3;
    }
    .cell-dosen-role {
        font-size: 0.7rem;
        color: #9ca3af;
    }

    /* Empty */
    .empty-state {
        padding: 56px 20px;
        text-align: center;
    }
    .empty-icon {
        width: 64px;
        height: 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        border-radius: 50%;
        margin-bottom: 14px;
        color: #9ca3af;
        font-size: 1.5rem;
    }
    .empty-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }
    .empty-text {
        font-size: 0.82rem;
        color: #9ca3af;
    }

    /* Summary */
    .summary-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 0;
        font-size: 0.8rem;
        color: #9ca3af;
    }
    .summary-bar strong {
        color: #374151;
        font-weight: 700;
    }

    @media (max-width: 576px) {
        .data-table thead th,
        .data-table tbody td {
            padding: 10px 12px;
        }
        .cell-catatan { max-width: 140px; }
        .filter-bar {
            padding: 12px 16px;
        }
        .filter-bar .filter-select {
            min-width: 150px;
            font-size: 0.78rem;
        }
        .btn-export-pdf i {
            margin-right: 0;
        }
    }
</style>

<!-- Filter Bar -->
<div class="filter-bar mb-4">
    <div class="d-flex align-items-center flex-wrap gap-2">
        <form method="GET" class="d-flex align-items-center gap-2 flex-fill" id="filterForm">
            <select name="jenis_bimbingan" class="filter-select flex-fill" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Jenis</option>
                <option value="proposal" {{ $filterJenis === 'proposal' ? 'selected' : '' }}>Proposal</option>
                <option value="seminar_hasil" {{ $filterJenis === 'seminar_hasil' ? 'selected' : '' }}>Seminar Hasil</option>
                <option value="laporan_skripsi" {{ $filterJenis === 'laporan_skripsi' ? 'selected' : '' }}>Ujian Sidang Akhir</option>
            </select>
        </form>
        <a href="{{ route('mahasiswa.bimbingan.export-riwayat', ['jenis_bimbingan' => $filterJenis]) }}"
            class="btn-export-pdf flex-shrink-0" target="_blank" title="Export data ke PDF">
            <i class="bi bi-file-earmark-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
        </a>
    </div>
    <div class="filter-label">
        <i class="bi bi-funnel" style="font-size:0.7rem;"></i>
        @if ($filterJenis)
            <span>Filter:</span>
            <span class="filter-active">
                @php
                    $label =
                        [
                            'proposal' => 'Proposal',
                            'seminar_hasil' => 'Seminar Hasil',
                            'laporan_skripsi' => 'Ujian Sidang Akhir',
                        ][$filterJenis] ?? 'Unknown';
                @endphp
                {{ $label }}
            </span>
        @else
            <span>Menampilkan semua jenis bimbingan</span>
        @endif
    </div>
</div>

<!-- Data Table -->
<div class="data-table-wrapper">
    <div class="table-responsive">
        <table class="data-table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="px-2 px-sm-3">No</th>
                    <th>Jenis Bimbingan</th>
                    <th>Tanggal</th>
                    <th>Tahapan Bimbingan</th>
                    <th>Dosen Pembimbing</th>
                    <th class="text-center">Status Paraf</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressRecords as $index => $record)
                    <tr>
                        <td class="cell-no px-2 px-sm-3">{{ $index + 1 }}</td>
                        <td>
                            @php
                                $jenis = $record->bimbingan->jenis_bimbingan;
                                $iconMap = [
                                    'proposal' => 'bi-file-earmark-text',
                                    'seminar_hasil' => 'bi-people',
                                    'laporan_skripsi' => 'bi-journal-bookmark',
                                ];
                                $labelMap = [
                                    'proposal' => 'Proposal',
                                    'seminar_hasil' => 'Seminar Hasil',
                                    'laporan_skripsi' => 'Ujian Sidang Akhir',
                                ];
                                $label = $labelMap[$jenis] ?? 'Unknown';
                                $icon = $iconMap[$jenis] ?? 'bi-file-earmark';
                            @endphp
                            <span class="badge-jenis type-{{ $jenis }}">
                                <i class="bi {{ $icon }}"></i>
                                {{ $label }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-date">
                                {{ $record->tanggal_bimbingan->format('d M Y') }}
                                <small>{{ $record->tanggal_bimbingan->format('H:i') }} WIB</small>
                            </span>
                        </td>
                        <td>
                            <span class="cell-catatan" title="{{ $record->catatan }}">
                                {{ Str::limit($record->catatan, 45) }}
                            </span>
                        </td>
                        <td>
                            <div class="cell-dosen" title="{{ $record->bimbingan->dosen->name ?? '-' }}">
                                <span class="cell-dosen-avatar"><i class="bi bi-person-fill"></i></span>
                                <span>
                                    <span class="cell-dosen-name d-block">{{ Str::limit($record->bimbingan->dosen->name ?? '-', 25) }}</span>
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if ($record->status === 'menunggu')
                                <span class="badge-status status-menunggu" title="Menunggu persetujuan dosen">
                                    <span class="status-dot"></span> Menunggu
                                </span>
                            @elseif($record->status === 'disetujui')
                                <span class="badge-status status-disetujui" title="Sudah disetujui">
                                    <span class="status-dot"></span> Disetujui
                                </span>
                            @else
                                <span class="badge-status status-ditolak" title="Ditolak oleh dosen">
                                    <span class="status-dot"></span> Ditolak
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-0">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-journal-x"></i>
                                </div>
                                <div class="empty-title">Belum Ada Riwayat</div>
                                <div class="empty-text">Riwayat bimbingan akan muncul setelah sesi bimbingan pertama.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Summary -->
@if ($progressRecords->isNotEmpty())
    <div class="summary-bar">
        <i class="bi bi-bar-chart-line" style="font-size:0.75rem;"></i>
        Total: <strong>{{ $progressRecords->count() }} sesi bimbingan</strong>
    </div>
@endif
@endsection
