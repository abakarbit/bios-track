@extends('layouts.app')
@section('title', 'Detail Bimbingan')
@section('page-header')@endsection
@section('page-title', 'Detail Bimbingan')

@section('content')
@include('shared.bimbingan.show')

<div class="row mt-3">
<div class="col-lg-5">
<div class="card">
    <div class="card-header"><i class="bi bi-chat-left-text me-2 text-primary"></i>Feedback Kaprodi</div>
    <div class="card-body">
        <form action="{{ route('kaprodi.bimbingan.feedback', $bimbingan) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Catatan / Feedback</label>
                <textarea name="catatan_kaprodi" class="form-control" rows="3" placeholder="Tambahkan catatan...">{{ $bimbingan->catatan_kaprodi }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send me-1"></i>Simpan Catatan</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
