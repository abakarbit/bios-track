@extends('layouts.app')
@section('title', 'Detail Ujian')
@section('page-header')@endsection
@section('page-title', 'Detail Jadwal Ujian')

@section('content')
@include('shared.ujian.show')

@if($ujian->status === 'disetujui_dosen')
<div class="row mt-3">
<div class="col-lg-6">
<div class="card border-warning">
    <div class="card-header bg-warning text-dark"><i class="bi bi-mortarboard me-2"></i>Keputusan Kaprodi</div>
    <div class="card-body">
        <form action="{{ route('kaprodi.ujian.approve', $ujian) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan_kaprodi" class="form-control" rows="3" placeholder="Berikan catatan atau alasan keputusan..."></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="action" value="disetujui" class="btn btn-success flex-fill"><i class="bi bi-check2-circle me-2"></i>Setujui Ujian</button>
                <button type="submit" name="action" value="ditolak" class="btn btn-danger" onclick="return confirm('Yakin tolak ujian ini?')"><i class="bi bi-x-circle me-2"></i>Tolak</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endif
@endsection
