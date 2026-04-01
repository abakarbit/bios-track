@extends('layouts.app')
@section('title', 'Riwayat Ujian')
@section('page-header')@endsection
@section('page-title', 'Riwayat Ujian')

@section('content')
@if($ujianByJenis->isEmpty())
<div class="card"><div class="card-body text-center py-5 text-muted"><i class="bi bi-archive fs-2 d-block mb-2"></i>Belum ada riwayat ujian.</div></div>
@else
@foreach($ujianByJenis as $jenis => $list)
@php
$jenisLabels = ['proposal'=>'Ujian Proposal','seminar_hasil'=>'Seminar Hasil','laporan_skripsi'=>'Ujian Sidang Akhir'];
$jenisColors = ['proposal'=>'primary','seminar_hasil'=>'success','laporan_skripsi'=>'danger'];
$color = $jenisColors[$jenis] ?? 'secondary';
@endphp
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-{{ $color }} fs-6">{{ $jenisLabels[$jenis] ?? $jenis }}</span>
        <span class="text-muted small">({{ $list->count() }} jadwal)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>#</th><th>Tanggal</th><th>Tempat</th><th>Pembimbing</th><th>Penguji</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($list as $i => $ujian)
                <tr>
                    <td class="text-muted small">{{ $i+1 }}</td>
                    <td>{{ $ujian->tanggal_ujian->format('d M Y H:i') }}</td>
                    <td>{{ $ujian->tempat_ujian }}</td>
                    <td class="small">{{ $ujian->pembimbing1->name ?? '-' }}@if($ujian->pembimbing2), {{ $ujian->pembimbing2->name }}@endif</td>
                    <td class="small">{{ $ujian->penguji1->name ?? '-' }}@if($ujian->penguji2), {{ $ujian->penguji2->name }}@endif</td>
                    <td>{!! $ujian->statusBadge !!}</td>
                    <td><a href="{{ route('mahasiswa.ujian.show', $ujian) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection
