<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Bimbingan</div>
            <div class="card-body">
                <table class="table table-borderless small mb-0">
                    <tr><th class="text-muted">Jenis</th><td><span class="badge {{ ['proposal'=>'bg-primary','seminar_hasil'=>'bg-success','laporan_skripsi'=>'bg-danger'][$bimbingan->jenis_bimbingan] ?? 'bg-secondary' }}">{{ $bimbingan->jenisLabel }}</span></td></tr>

                    @if($bimbingan->topik)<tr><th class="text-muted">Topik</th><td>{{ $bimbingan->topik }}</td></tr>@endif
                    <tr><th class="text-muted">Mahasiswa</th><td>{{ ucwords($bimbingan->mahasiswa->name) }}</td></tr>
                    <tr><th class="text-muted">NIM</th><td>{{ $bimbingan->mahasiswa->nim ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Pembimbing {{ $bimbingan->pembimbing }}</th><td>{{ ucwords($bimbingan->dosen->name) }}</td></tr>
                    <tr><th class="text-muted">Status</th><td>{!! $bimbingan->statusBadge !!}</td></tr>
                </table>
                @if($bimbingan->catatan_mahasiswa)
                <div class="alert alert-light mt-3 mb-0 p-2">
                    <strong><i class="bi bi-chat-text me-1 text-info"></i>Catatan Mahasiswa:</strong><br>
                    <small>{{ $bimbingan->catatan_mahasiswa }}</small>
                </div>
                @endif
                @if($bimbingan->catatan_dosen)
                <div class="alert alert-info mt-2 mb-0 p-2">
                    <strong><i class="bi bi-chat-quote me-1"></i>Catatan Dosen:</strong><br>
                    <small>{{ $bimbingan->catatan_dosen }}</small>
                </div>
                @endif
            </div>
        </div>

        @if((Auth::user()->isDosen() || Auth::user()->isKaprodi()) && Auth::id() == $bimbingan->dosen_id)
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-gear me-2"></i>Aksi Dosen</div>
            <div class="card-body">
                @if($bimbingan->status === 'menunggu')
                <form action="{{ route('dosen.bimbingan.approve', $bimbingan) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan (opsional)</label>
                        <textarea name="catatan_dosen" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="disetujui" class="btn btn-success btn-sm flex-fill"><i class="bi bi-check2-circle me-1"></i>Setujui</button>
                        <button type="submit" name="action" value="ditolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak bimbingan ini?')"><i class="bi bi-x-circle me-1"></i>Tolak</button>
                    </div>
                </form>
                @elseif($bimbingan->status === 'disetujui')
                <form action="{{ route('dosen.bimbingan.selesai', $bimbingan) }}" method="POST" onsubmit="return confirm('Tandai bimbingan ini selesai?')">
                    @csrf
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-flag-fill me-1"></i>Tandai Selesai</button>
                </form>
                @elseif($bimbingan->status === 'selesai')
                <form action="{{ route('dosen.bimbingan.tidak-selesai', $bimbingan) }}" method="POST" onsubmit="return confirm('Kembalikan ke status disetujui?')">
                    @csrf
                    <button class="btn btn-warning btn-sm w-100"><i class="bi bi-arrow-counterclockwise me-1"></i>Tandai Belum Selesai</button>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-list-ol me-2 text-success"></i>Progress Bimbingan ({{ $bimbingan->progresses->count() }})</span>
                @if(Auth::user()->isMahasiswa() && $bimbingan->status === 'disetujui')
                <a href="{{ route('mahasiswa.bimbingan.progress.create', $bimbingan) }}" class="btn btn-sm btn-success"><i class="bi bi-plus me-1"></i>Tambah</a>
                @endif
            </div>
            <div class="card-body p-0">
                @forelse($bimbingan->progresses->sortByDesc('created_at') as $p)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <small class="text-muted">{{ $p->tanggal_bimbingan->format('d M Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ $p->catatan }}</p>
                            @if($p->file_path)
                            <a href="{{ Storage::url($p->file_path) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem"><i class="bi bi-paperclip me-1"></i>Lampiran</a>
                            @endif
                            @if($p->catatan_dosen)
                            <div class="mt-2 p-2 rounded" style="background:#f0f8ff;border-left:3px solid #17a2b8">
                                <small><i class="bi bi-chat-quote me-1 text-info"></i><strong>Catatan Dosen:</strong> {{ $p->catatan_dosen }}</small>
                            </div>
                            @endif
                        </div>
                        <div class="ms-3 text-end">
                            {!! $p->statusBadge !!}
                            @if(Auth::user()->isMahasiswa() && Auth::id() == $bimbingan->mahasiswa_id && $p->status === 'menunggu')
                            <div class="mt-2">
                                <a href="{{ route('mahasiswa.bimbingan.progress.edit', $p) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            </div>
                            @elseif((Auth::user()->isDosen() || Auth::user()->isKaprodi()) && Auth::id() == $bimbingan->dosen_id && $p->status === 'menunggu')
                            <div class="mt-2">
                                <form action="{{ route('dosen.progress.approve', $p) }}" method="POST">
                                    @csrf
                                    <div class="d-flex text-end gap-1">
                                        <button type="submit" name="action" value="disetujui" class="btn btn-sm btn-success" title="Paraf"><i class="bi bi-pen"></i></button>
                                        {{-- <button type="submit" name="action" value="ditolak" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak progress?')"><i class="bi bi-x"></i></button> --}}
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-text fs-3 d-block mb-2"></i>Belum ada progress.
                    @if(Auth::user()->isMahasiswa() && $bimbingan->status === 'disetujui')
                    <br><a href="{{ route('mahasiswa.bimbingan.progress.create', $bimbingan) }}" class="btn btn-sm btn-success mt-2">Tambah Progress</a>
                    @endif
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
