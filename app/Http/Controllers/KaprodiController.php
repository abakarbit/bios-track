<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\BimbinganProgress;
use App\Models\Notification;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaprodiController extends Controller
{
    // List semua mahasiswa dan progressnya
    public function mahasiswaList()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->addSelect([
                'bimbingans_count' => Bimbingan::selectRaw('count(distinct jenis_bimbingan)')
                    ->whereColumn('mahasiswa_id', 'users.id')
            ])
            ->withCount('ujians')
            ->orderBy('name')
            ->paginate(20);
        return view('kaprodi.mahasiswa.index', compact('mahasiswas'));
    }

    public function mahasiswaDetail(User $mahasiswa)
    {
        if ($mahasiswa->role !== 'mahasiswa') abort(404);
        $bimbingans = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->with(['dosen', 'progresses'])
            ->withCount(['progresses'])
            ->orderBy('jenis_bimbingan')->get();

        $ujians = Ujian::where('mahasiswa_id', $mahasiswa->id)
            ->with(['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen'])->get();
        return view('kaprodi.mahasiswa.detail', compact('mahasiswa', 'bimbingans', 'ujians'));
    }

    // Kaprodi approve ujian setelah semua dosen approve
    public function ujianList()
    {
        $ujians = Ujian::with(['mahasiswa', 'pembimbing1', 'penguji1'])
            ->orderBy('status')
            ->orderBy('tanggal_ujian')
            ->paginate(15);
        return view('kaprodi.ujian.index', compact('ujians'));
    }

    public function ujianShow(Ujian $ujian)
    {
        $ujian->load(['mahasiswa', 'pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen']);
        return view('kaprodi.ujian.show', compact('ujian'));
    }

    public function ujianApprove(Request $request, Ujian $ujian)
    {
        if ($ujian->status !== 'disetujui_dosen') {
            return back()->with('error', 'Ujian harus disetujui semua dosen terlebih dahulu.');
        }

        $request->validate([
            'action' => 'required|in:disetujui,ditolak',
            'catatan_kaprodi' => 'nullable|string|max:500',
        ]);

        $newStatus = $request->action === 'disetujui' ? 'disetujui_kaprodi' : 'ditolak';
        $ujian->update([
            'status_kaprodi' => $request->action,
            'status' => $newStatus,
            'catatan_kaprodi' => $request->catatan_kaprodi,
            'approved_kaprodi_at' => $request->action === 'disetujui' ? now() : null,
        ]);

        $statusLabel = $request->action === 'disetujui' ? 'disetujui' : 'ditolak';
        Notification::send(
            $ujian->mahasiswa_id,
            'Jadwal Ujian Disetujui Kaprodi',
            "Jadwal ujian Anda telah {$statusLabel} oleh Kaprodi. " . ($request->catatan_kaprodi ?? ''),
            $request->action === 'disetujui' ? 'success' : 'danger',
            route('mahasiswa.ujian.show', $ujian->id),
            Ujian::class,
            $ujian->id,
            Auth::id(),
            "Jenis: " . Ujian::JENIS[$ujian->jenis_ujian] . ". Tanggal: {$ujian->tanggal_ujian}"
        );

        // Notify dosen
        $dosens = collect([
            $ujian->dosen_pembimbing1_id, $ujian->dosen_pembimbing2_id,
            $ujian->dosen_penguji1_id, $ujian->dosen_penguji2_id,
        ])->filter()->unique();
        foreach ($dosens as $dosenId) {
            Notification::send($dosenId, 'Jadwal Ujian Disetujui Kaprodi',
                "Jadwal ujian {$ujian->mahasiswa->name} telah {$statusLabel} oleh Kaprodi.",
                $request->action === 'disetujui' ? 'success' : 'info',
                route('dosen.ujian.show', $ujian->id), Ujian::class, $ujian->id, Auth::id(), "Jenis: " . Ujian::JENIS[$ujian->jenis_ujian]);
        }

        return back()->with('success', "Ujian berhasil {$statusLabel} oleh Kaprodi.");
    }

    // All bimbingans overview
    public function bimbinganList()
    {
        $query = BimbinganProgress::with(['bimbingan.mahasiswa', 'bimbingan.dosen']);

        // Filter status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter jenis bimbingan
        if (request('jenis')) {
            $query->whereHas('bimbingan', function($q) {
                $q->where('jenis_bimbingan', request('jenis'));
            });
        }

        // Search mahasiswa
        if (request('search')) {
            $query->whereHas('bimbingan.mahasiswa', function($q) {
                $q->where('name', 'like', '%'.request('search').'%')
                  ->orWhere('nim', 'like', '%'.request('search').'%');
            });
        }

        $progresses = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('kaprodi.bimbingan.index', compact('progresses'));
    }

    public function bimbinganShow(Bimbingan $bimbingan)
    {
        $bimbingan->load(['mahasiswa', 'dosen', 'progresses']);
        return view('kaprodi.bimbingan.show', compact('bimbingan'));
    }

    // Kaprodi can add feedback/catatan to bimbingan
    public function bimbinganFeedback(Request $request, Bimbingan $bimbingan)
    {
        $request->validate(['catatan_dosen' => 'required|string|max:500']);
        $bimbingan->update(['catatan_dosen' => $request->catatan_dosen]);

        Notification::send($bimbingan->mahasiswa_id, 'Catatan Kaprodi',
            'Kaprodi memberikan catatan pada bimbingan Anda.',
            'info', route('mahasiswa.bimbingan.show', $bimbingan->id), Bimbingan::class, $bimbingan->id, Auth::id(), $request->catatan_dosen);

        return back()->with('success', 'Catatan berhasil disimpan.');
    }
}
