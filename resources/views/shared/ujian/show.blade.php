





<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Ujian</div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th class="text-muted">Jenis</th><td><span class="badge {{ ['proposal'=>'bg-primary','seminar_hasil'=>'bg-success','laporan_skripsi'=>'bg-danger'][$ujian->jenis_ujian] ?? 'bg-secondary' }} fs-6">{{ $ujian->jenisLabel }}</span></td></tr>
                    <tr><th class="text-muted">Tanggal</th><td><strong>{{ $ujian->tanggal_ujian->format('l, d M Y') }}</strong></td></tr>
                    <tr><th class="text-muted">Tempat</th><td>{{ $ujian->tempat_ujian }}</td></tr>
                    <tr><th class="text-muted">Mahasiswa</th><td>{{ $ujian->mahasiswa->name }}</td></tr>
                    <tr><th class="text-muted">NIM</th><td>{{ $ujian->mahasiswa->nim ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Status</th><td>{!! $ujian->statusBadge !!}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-people me-2 text-success"></i>Panel Penguji</div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded" style="background:#f0fff4">
                    <div><i class="bi bi-person-check text-success me-2"></i><strong>Pembimbing 1:</strong> {{ $ujian->pembimbing1->name ?? '-' }}</div>
                    <span class="badge {{ $ujian->status_pembimbing1 === 'disetujui' ? 'bg-success' : ($ujian->status_pembimbing1 === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($ujian->status_pembimbing1) }}</span>
                </div>
                @if($ujian->pembimbing2)
                <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded" style="background:#f0fff4">
                    <div><i class="bi bi-person-check text-success me-2"></i><strong>Pembimbing 2:</strong> {{ $ujian->pembimbing2->name }}</div>
                    <span class="badge {{ $ujian->status_pembimbing2 === 'disetujui' ? 'bg-success' : ($ujian->status_pembimbing2 === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($ujian->status_pembimbing2) }}</span>
                </div>
                @endif
                <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded" style="background:#f5f5f5">
                    <div><i class="bi bi-person-lines-fill text-secondary me-2"></i><strong>Penguji 1:</strong> {{ $ujian->penguji1->name ?? '-' }}</div>
                    <span class="badge bg-secondary">Informasi</span>
                </div>
                @if($ujian->penguji2)
                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background:#f5f5f5">
                    <div><i class="bi bi-person-lines-fill text-secondary me-2"></i><strong>Penguji 2:</strong> {{ $ujian->penguji2->name }}</div>
                    <span class="badge bg-secondary">Informasi</span>
                </div>
                @endif
                <div class="d-flex align-items-center justify-content-between p-2 rounded mt-2" style="background:#f0f0ff">
                    <div><i class="bi bi-mortarboard text-primary me-2"></i><strong>Kaprodi</strong></div>
                    <span class="badge {{ $ujian->status_kaprodi === 'disetujui' ? 'bg-success' : ($ujian->status_kaprodi === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($ujian->status_kaprodi) }}</span>
                </div>
            </div>
        </div>

        {{-- Mahasiswa edit action --}}
        @if(Auth::user()->isMahasiswa() && $ujian->mahasiswa_id == Auth::id() && $ujian->status === 'menunggu')
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Aksi Mahasiswa</div>
            <div class="card-body">
                <a href="{{ route('mahasiswa.ujian.edit', $ujian) }}" class="btn btn-warning btn-sm w-100"><i class="bi bi-pencil-square me-2"></i>Edit Jadwal Ujian</a>
            </div>
        </div>
        @endif

        {{-- Dosen approve action --}}
        @if(Auth::user()->isDosen() || Auth::user()->isKaprodi())
        @php
            $userId = Auth::id();
            $canApprove = false; $approveLabel = '';
            if ($ujian->dosen_pembimbing1_id == $userId && $ujian->status_pembimbing1 === 'menunggu') { $canApprove = true; $approveLabel = 'Setujui (Pembimbing 1)'; }
            elseif ($ujian->dosen_pembimbing2_id == $userId && $ujian->status_pembimbing2 === 'menunggu') { $canApprove = true; $approveLabel = 'Setujui (Pembimbing 2)'; }
        @endphp
        @if($canApprove)
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-pen me-2"></i>Aksi Saya</div>
            <div class="card-body">
                <form action="{{ route('dosen.ujian.approve', $ujian) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="disetujui" class="btn btn-success btn-sm flex-fill"><i class="bi bi-check2-circle me-1"></i>{{ $approveLabel }}</button>
                        <button type="submit" name="action" value="ditolak" class="btn btn-danger btn-sm" onclick="return confirm('Yakin tolak jadwal ujian ini?')"><i class="bi bi-x-circle me-1"></i>Tolak</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endif
    </div>

    <div class="col-lg-7">
        @if($ujian->catatan_kaprodi)
        <div class="alert alert-info"><i class="bi bi-chat-quote me-2"></i><strong>Catatan Kaprodi:</strong> {{ $ujian->catatan_kaprodi }}</div>
        @endif

        @if($ujian->dokumen)
        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark-check me-2 text-success"></i>Dokumen Ujian</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size:2rem"></i>
                            <div class="fw-semibold mt-2">Berkas BAP</div>
                            <a href="{{ Storage::url($ujian->dokumen->berkas_bap) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-download me-1"></i>Download</a>
                        </div>
                    </div>
                    @if($ujian->dokumen->berkas_nilai)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size:2rem"></i>
                            <div class="fw-semibold mt-2">Berkas Nilai</div>
                            <a href="{{ Storage::url($ujian->dokumen->berkas_nilai) }}" target="_blank" class="btn btn-sm btn-outline-success mt-2"><i class="bi bi-download me-1"></i>Download</a>
                        </div>
                    </div>
                    @endif
                </div>
                @if($ujian->dokumen->nilai)
                <div class="mt-3 alert alert-success"><strong>Nilai Ujian: {{ $ujian->dokumen->nilai }}</strong>@if($ujian->dokumen->keterangan)<br><small>{{ $ujian->dokumen->keterangan }}</small>@endif</div>
                @endif
            </div>
        </div>
        @elseif($ujian->status === 'disetujui_kaprodi' && Auth::user()->isMahasiswa())
        <div class="card">
            <div class="card-header"><i class="bi bi-upload me-2 text-primary"></i>Upload Dokumen Ujian</div>
            <div class="card-body text-center py-4">
                <i class="bi bi-file-earmark-arrow-up fs-2 text-primary mb-3 d-block"></i>
                <p class="text-muted">Ujian telah disetujui. Silakan upload Berkas BAP setelah ujian terlaksana.</p>
                <a href="{{ route('mahasiswa.ujian.dokumen.create', $ujian) }}" class="btn btn-primary"><i class="bi bi-upload me-2"></i>Upload Berkas BAP</a>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4 text-muted">
                <i class="bi bi-hourglass-split fs-2 d-block mb-2"></i>
                @if($ujian->status === 'selesai') Ujian telah selesai.
                @else Dokumen tersedia setelah ujian disetujui Kaprodi dan terlaksana.
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
