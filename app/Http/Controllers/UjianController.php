<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Notification;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    // Mahasiswa: list ujian
    public function index()
    {
        $user = Auth::user();
        $ujians = Ujian::where('mahasiswa_id', $user->id)
            ->with(['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen'])
            ->orderBy('tanggal_ujian', 'asc')
            ->get();
        return view('mahasiswa.ujian.index', compact('ujians'));
    }

    public function create()
    {
        $user = Auth::user();

        // Check if all bimbingan is selesai
        $pendingBimbingan = Bimbingan::where('mahasiswa_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'ditolak')
            ->count();

        if ($pendingBimbingan > 0) {
            return redirect()->route('mahasiswa.bimbingan.index')
                ->with('warning', 'Semua bimbingan harus selesai sebelum membuat jadwal ujian.');
        }

        $availableJenis = $this->getAvailableUjianJenis($user->id);

        if (empty($availableJenis)) {
            return redirect()->route('mahasiswa.ujian.index')
                ->with('info', 'Tidak ada jenis ujian yang tersedia saat ini.');
        }

        $dosens = User::whereIn('role', ['dosen', 'kaprodi'])->where('is_active', true)->get();
        return view('mahasiswa.ujian.create', compact('availableJenis', 'dosens'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Verify all bimbingan is selesai
        $pendingBimbingan = Bimbingan::where('mahasiswa_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'ditolak')
            ->count();

        if ($pendingBimbingan > 0) {
            return redirect()->route('mahasiswa.bimbingan.index')
                ->with('warning', 'Semua bimbingan harus selesai sebelum membuat jadwal ujian.');
        }

        $request->validate([
            'jenis_ujian' => 'required|in:proposal,seminar_hasil,laporan_skripsi',
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required|string|max:255',
            'dosen_pembimbing1_id' => 'required|exists:users,id',
            'dosen_pembimbing2_id' => 'nullable|exists:users,id',
            'dosen_penguji1_id' => 'required|exists:users,id',
            'dosen_penguji2_id' => 'nullable|exists:users,id',
        ]);

        $availableJenis = $this->getAvailableUjianJenis($user->id);
        if (!in_array($request->jenis_ujian, $availableJenis)) {
            return back()->withErrors(['jenis_ujian' => 'Jenis ujian tidak tersedia. Selesaikan bimbingan terlebih dahulu.']);
        }

        // Check no duplicate pending/approved ujian
        $existingUjian = Ujian::where('mahasiswa_id', $user->id)
            ->where('jenis_ujian', $request->jenis_ujian)
            ->whereNotIn('status', ['ditolak'])
            ->first();

        if ($existingUjian) {
            return back()->withErrors(['jenis_ujian' => 'Anda sudah memiliki jadwal ujian untuk jenis ini.']);
        }

        $statusPembimbing2 = $request->dosen_pembimbing2_id ? 'menunggu' : 'tidak_ada';
        $statusPenguji1 = 'disetujui'; // Penguji hanya informasi, tidak perlu approve
        $statusPenguji2 = $request->dosen_penguji2_id ? 'disetujui' : 'tidak_ada';

        $ujian = Ujian::create([
            'mahasiswa_id' => $user->id,
            'jenis_ujian' => $request->jenis_ujian,
            'tanggal_ujian' => $request->tanggal_ujian,
            'tempat_ujian' => $request->tempat_ujian,
            'dosen_pembimbing1_id' => $request->dosen_pembimbing1_id,
            'dosen_pembimbing2_id' => $request->dosen_pembimbing2_id,
            'dosen_penguji1_id' => $request->dosen_penguji1_id,
            'dosen_penguji2_id' => $request->dosen_penguji2_id,
            'status_pembimbing1' => 'menunggu',
            'status_pembimbing2' => $statusPembimbing2,
            'status_penguji1' => $statusPenguji1,
            'status_penguji2' => $statusPenguji2,
            'status_kaprodi' => 'menunggu',
            'status' => 'menunggu',
        ]);

        $jenisLabel = Ujian::JENIS[$request->jenis_ujian];
        $msg = "Mahasiswa {$user->name} mengajukan jadwal {$jenisLabel} pada {$request->tanggal_ujian}";

        // Notify only pembimbing (not penguji)
        $dosens = collect([
            $request->dosen_pembimbing1_id,
            $request->dosen_pembimbing2_id,
        ])->filter()->unique();

        foreach ($dosens as $dosenId) {
            Notification::send($dosenId, "Jadwal {$jenisLabel} Baru", $msg, 'info',
                route('dosen.ujian.show', $ujian->id), Ujian::class, $ujian->id, $user->id, "Tanggal: {$request->tanggal_ujian}. Tempat: {$request->tempat_ujian}");
        }

        return redirect()->route('mahasiswa.ujian.index')
            ->with('success', 'Jadwal ujian berhasil diajukan. Menunggu persetujuan dosen.');
    }

    public function show(Ujian $ujian)
    {
        $user = Auth::user();
        if ($user->role === 'mahasiswa' && $ujian->mahasiswa_id !== $user->id) abort(403);
        $ujian->load(['mahasiswa', 'pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen']);
        return view('mahasiswa.ujian.show', compact('ujian'));
    }

    public function edit(Ujian $ujian)
    {
        $user = Auth::user();
        if ($ujian->mahasiswa_id !== $user->id) abort(403);
        if ($ujian->status !== 'menunggu') {
            return back()->with('error', 'Hanya ujian yang belum disetujui semua dosen yang bisa diedit.');
        }
        $dosens = User::whereIn('role', ['dosen', 'kaprodi'])->where('is_active', true)->get();
        $ujian->load(['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2']);
        return view('mahasiswa.ujian.edit', compact('ujian', 'dosens'));
    }

    public function update(Request $request, Ujian $ujian)
    {
        $user = Auth::user();
        if ($ujian->mahasiswa_id !== $user->id) abort(403);
        if ($ujian->status !== 'menunggu') {
            return back()->with('error', 'Hanya ujian yang belum disetujui semua dosen yang bisa diedit.');
        }

        $request->validate([
            'jenis_ujian' => 'required|in:proposal,seminar_hasil,laporan_skripsi',
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required|string|max:255',
            'dosen_pembimbing1_id' => 'required|exists:users,id',
            'dosen_pembimbing2_id' => 'nullable|exists:users,id',
            'dosen_penguji1_id' => 'required|exists:users,id',
            'dosen_penguji2_id' => 'nullable|exists:users,id',
        ]);

        $statusPembimbing2 = $request->dosen_pembimbing2_id ? 'menunggu' : 'tidak_ada';
        $statusPenguji1 = 'disetujui'; // Penguji hanya informasi, tidak perlu approve
        $statusPenguji2 = $request->dosen_penguji2_id ? 'disetujui' : 'tidak_ada';

        $ujian->update([
            'jenis_ujian' => $request->jenis_ujian,
            'tanggal_ujian' => $request->tanggal_ujian,
            'tempat_ujian' => $request->tempat_ujian,
            'dosen_pembimbing1_id' => $request->dosen_pembimbing1_id,
            'dosen_pembimbing2_id' => $request->dosen_pembimbing2_id,
            'dosen_penguji1_id' => $request->dosen_penguji1_id,
            'dosen_penguji2_id' => $request->dosen_penguji2_id,
            'status_pembimbing1' => 'menunggu',
            'status_pembimbing2' => $statusPembimbing2,
            'status_penguji1' => $statusPenguji1,
            'status_penguji2' => $statusPenguji2,
        ]);

        $jenisLabel = Ujian::JENIS[$request->jenis_ujian];
        $msg = "Mahasiswa {$user->name} merevisi jadwal {$jenisLabel} pada {$request->tanggal_ujian}";

        // Notify only pembimbing about the update
        $dosens = collect([
            $request->dosen_pembimbing1_id,
            $request->dosen_pembimbing2_id,
        ])->filter()->unique();

        foreach ($dosens as $dosenId) {
            Notification::send($dosenId, "Jadwal {$jenisLabel} Diperbarui", $msg, 'info',
                route('dosen.ujian.show', $ujian->id), Ujian::class, $ujian->id, $user->id, "Tanggal: {$request->tanggal_ujian}. Tempat: {$request->tempat_ujian}");
        }

        return redirect()->route('mahasiswa.ujian.index')
            ->with('success', 'Jadwal ujian berhasil diperbarui dan notifikasi dikirim ke dosen.');
    }

    // Dosen: list ujian terkait dosen
    public function dosenIndex()
    {
        $user = Auth::user();
        $ujians = Ujian::where(function ($q) use ($user) {
                $q->where('dosen_pembimbing1_id', $user->id)
                    ->orWhere('dosen_pembimbing2_id', $user->id)
                    ->orWhere('dosen_penguji1_id', $user->id)
                    ->orWhere('dosen_penguji2_id', $user->id);
            })
            ->with(['mahasiswa', 'pembimbing1'])
            ->orderBy('status')
            ->orderBy('tanggal_ujian', 'desc')
            ->paginate(15);
        return view('dosen.ujian.index', compact('ujians'));
    }

    public function dosenShow(Ujian $ujian)
    {
        $user = Auth::user();
        $isInvolved = in_array($user->id, [
            $ujian->dosen_pembimbing1_id, $ujian->dosen_pembimbing2_id,
            $ujian->dosen_penguji1_id, $ujian->dosen_penguji2_id,
        ]);
        if (!$isInvolved) abort(403);
        $ujian->load(['mahasiswa', 'pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen']);
        return view('dosen.ujian.show', compact('ujian'));
    }

    // Dosen: approve ujian (only pembimbing can approve)
    public function dosenApprove(Request $request, Ujian $ujian)
    {
        $user = Auth::user();
        $request->validate(['action' => 'required|in:disetujui,ditolak', 'catatan' => 'nullable|string|max:500']);

        // Only pembimbing can approve, not penguji
        $isPembimbing = ($ujian->dosen_pembimbing1_id === $user->id) || ($ujian->dosen_pembimbing2_id === $user->id);
        if (!$isPembimbing) abort(403, 'Hanya pembimbing yang dapat melakukan persetujuan ujian.');

        $field = null;
        if ($ujian->dosen_pembimbing1_id === $user->id) $field = 'status_pembimbing1';
        elseif ($ujian->dosen_pembimbing2_id === $user->id) $field = 'status_pembimbing2';

        if (!$field) abort(403);

        $ujian->update([$field => $request->action]);
        $ujian->refresh();

        // Check if all pembimbing approved
        if ($ujian->isAllPembimbingApproved() && $request->action === 'disetujui') {
            $ujian->update(['status' => 'disetujui_dosen']);
            // Notify kaprodi
            $kaprodi = User::where('role', 'kaprodi')->first();
            if ($kaprodi) {
                $jenisLabel = Ujian::JENIS[$ujian->jenis_ujian];
                Notification::send($kaprodi->id, 'Jadwal Ujian Siap Disetujui',
                    "Jadwal ujian mahasiswa {$ujian->mahasiswa->name} telah disetujui semua pembimbing. Menunggu persetujuan Kaprodi.",
                    'warning', route('kaprodi.ujian.show', $ujian->id), Ujian::class, $ujian->id, $user->id, "Jenis: {$jenisLabel}. Semua pembimbing telah setuju.");
            }
        }

        $statusLabel = $request->action === 'disetujui' ? 'disetujui' : 'ditolak';
        Notification::send($ujian->mahasiswa_id, 'Status Ujian Diperbarui',
            "Jadwal ujian Anda telah {$statusLabel} oleh " . $user->name,
            $request->action === 'disetujui' ? 'success' : 'danger',
            route('mahasiswa.ujian.show', $ujian->id), Ujian::class, $ujian->id, $user->id, "Dari: {$user->name}");

        return back()->with('success', "Ujian berhasil {$statusLabel}.");
    }

    // Mahasiswa riwayat ujian
    public function riwayat()
    {
        $user = Auth::user();
        $ujians = Ujian::where('mahasiswa_id', $user->id)
            ->with(['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2', 'dokumen'])
            ->orderBy('jenis_ujian')
            ->orderBy('tanggal_ujian', 'desc')
            ->get();
        $ujianByJenis = $ujians->groupBy('jenis_ujian');
        return view('mahasiswa.ujian.riwayat', compact('ujianByJenis'));
    }

    private function getAvailableUjianJenis(int $mahasiswaId): array
    {
        $order = ['proposal', 'seminar_hasil', 'laporan_skripsi'];
        $available = [];

        foreach ($order as $jenis) {
            // Check if this ujian already completed
            $hasDoneUjian = Ujian::where('mahasiswa_id', $mahasiswaId)
                ->where('jenis_ujian', $jenis)
                ->where('status', 'selesai')
                ->exists();
            if ($hasDoneUjian) continue;

            // Check if bimbingan for this type is selesai
            $hasDoneBimbingan = Bimbingan::where('mahasiswa_id', $mahasiswaId)
                ->where('jenis_bimbingan', $jenis)
                ->where('status', 'selesai')
                ->exists();
            if (!$hasDoneBimbingan) break;

            $available[] = $jenis;
            break;
        }
        return $available;
    }
}
