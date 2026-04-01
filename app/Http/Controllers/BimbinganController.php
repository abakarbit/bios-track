<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\BimbinganProgress;
use App\Models\Notification;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BimbinganController extends Controller
{
    // Mahasiswa: list bimbingan milik sendiri
    public function index()
    {
        $user = Auth::user();
        $bimbingans = Bimbingan::where('mahasiswa_id', $user->id)
            ->with('dosen')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();
        return view('mahasiswa.bimbingan.index', compact('bimbingans'));
    }

    public function create()
    {
        $user = Auth::user();
        $availableJenis = $this->getAvailableJenis($user->id);

        if (empty($availableJenis)) {
            return redirect()->route('mahasiswa.bimbingan.index')
                ->with('info', 'Semua jenis bimbingan telah selesai.');
        }

        $dosens = User::where('role', 'dosen')->orWhere('role', 'kaprodi')
            ->where('is_active', true)->get();

        return view('mahasiswa.bimbingan.create', compact('availableJenis', 'dosens'));
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        $request->validate([
            'jenis_bimbingan' => 'required|in:proposal,seminar_hasil,laporan_skripsi',
            'dosen_id' => 'required|exists:users,id',
            'pembimbing' => 'required|in:1,2',
            'topik' => 'nullable|string|max:255',
            'catatan_mahasiswa' => 'nullable|string|max:500',
        ]);

        // Check if jenis is allowed
        // $availableJenis = $this->getAvailableJenis($user->id);
        // if (!in_array($request->jenis_bimbingan, $availableJenis)) {
        //     return back()->withErrors(['jenis_bimbingan' => 'Anda harus menyelesaikan jenis bimbingan sebelumnya terlebih dahulu.']);
        // }

        //Check dosen pembiming sudah ada apa belum
        $existingPembimbing = Bimbingan::where('mahasiswa_id', $user->id)
            ->where('jenis_bimbingan', $request->jenis_bimbingan)
            ->where('pembimbing', $request->pembimbing)
            ->where('status', '!=', 'ditolak')
            ->first();
        if ($existingPembimbing) {
            return back()->withErrors(['pembimbing' => 'Pembimbing ' . $request->pembimbing . ' sudah ada untuk jenis bimbingan ini.']);
        }

        // Check dosen pembimbing 1 atau 2
        $pembimbing = Bimbingan::where('mahasiswa_id', $user->id)
            ->where('jenis_bimbingan', $request->jenis_bimbingan)
            ->where('pembimbing', $request->pembimbing)
            ->where('status', '!=', 'ditolak')
            ->first();
        if ($pembimbing) {
            return back()->withErrors(['pembimbing' => 'Pembimbing ' . $request->pembimbing . ' sudah ada untuk jenis bimbingan ini.']);
        }

        // // Check no active bimbingan for same jenis
        // $activeBimbingan = Bimbingan::where('mahasiswa_id', $user->id)
        //     ->where('jenis_bimbingan', $request->jenis_bimbingan)
        //     ->whereIn('status', ['menunggu', 'disetujui'])
        //     ->first();

        // if ($activeBimbingan) {
        //     return back()->withErrors(['jenis_bimbingan' => 'Anda sudah memiliki bimbingan aktif untuk jenis ini.']);
        // }

        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $user->id,
            'dosen_id' => $request->dosen_id,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'topik' => $request->topik,
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
            'pembimbing' => $request->pembimbing,
            'status' => 'menunggu',
        ]);

        // Notify dosen
        Notification::send(
            $request->dosen_id,
            'Jadwal Bimbingan Baru',
            "Mahasiswa {$user->name} mengajukan jadwal bimbingan " . Bimbingan::JENIS[$request->jenis_bimbingan] . " pada " . $request->tanggal_bimbingan,
            'info',
            route('dosen.bimbingan.show', $bimbingan->id),
            Bimbingan::class,
            $bimbingan->id,
            $user->id,
            "Topik: {$request->topik}"
        );

        return redirect()->route('mahasiswa.bimbingan.index')
            ->with('success', 'Jadwal bimbingan berhasil diajukan. Menunggu persetujuan dosen.');
    }

    public function show(Bimbingan $bimbingan)
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa' && $bimbingan->mahasiswa_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'dosen' && $bimbingan->dosen_id !== $user->id) {
            abort(403);
        }

        $bimbingan->load(['mahasiswa', 'dosen', 'progresses']);
        return view('mahasiswa.bimbingan.show', compact('bimbingan'));
    }

    // Dosen: list bimbingan oleh dosen
    public function dosenIndex()
    {
        $user = Auth::user();
        $query = Bimbingan::where('dosen_id', $user->id)
            ->with(['mahasiswa', 'progresses']);

        // Filter status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter jenis bimbingan
        if (request('jenis')) {
            $query->where('jenis_bimbingan', request('jenis'));
        }

        // Search mahasiswa
        if (request('search')) {
            $query->whereHas('mahasiswa', function($q) {
                $q->where('name', 'like', '%'.request('search').'%')
                  ->orWhere('nim', 'like', '%'.request('search').'%');
            });
        }

        $bimbingans = $query->orderBy('status')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->paginate(15);
        return view('dosen.bimbingan.index', compact('bimbingans'));
    }

    public function dosenShow(Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }
        $bimbingan->load(['mahasiswa', 'dosen', 'progresses']);
        return view('dosen.bimbingan.show', compact('bimbingan'));
    }

    // Dosen: approve / tolak bimbingan
    public function approve(Request $request, Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }
        $request->validate(['action' => 'required|in:disetujui,ditolak', 'catatan_dosen' => 'nullable|string|max:500']);

        $bimbingan->update([
            'status' => $request->action,
            'catatan_dosen' => $request->catatan_dosen,
            'approved_at' => $request->action === 'disetujui' ? now() : null,
        ]);

        $statusLabel = $request->action === 'disetujui' ? 'disetujui' : 'ditolak';
        Notification::send(
            $bimbingan->mahasiswa_id,
            'Status Bimbingan Diperbarui',
            "Jadwal bimbingan Anda telah {$statusLabel} oleh " . Auth::user()->name,
            $request->action === 'disetujui' ? 'success' : 'danger',
            route('mahasiswa.bimbingan.show', $bimbingan->id),
            Bimbingan::class,
            $bimbingan->id,
            Auth::id(),
            "Jenis: " . Bimbingan::JENIS[$bimbingan->jenis_bimbingan] . ". Catatan: {$request->catatan_dosen}"
        );

        return back()->with('success', "Bimbingan berhasil {$statusLabel}.");
    }

    // Dosen: tandai bimbingan selesai (semua progress sudah diparaf)
    public function markSelesai(Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }
        $bimbingan->update(['status' => 'selesai', 'selesai_at' => now()]);

        Notification::send(
            $bimbingan->mahasiswa_id,
            'Bimbingan Selesai',
            "Bimbingan " . Bimbingan::JENIS[$bimbingan->jenis_bimbingan] . " Anda telah dinyatakan selesai oleh " . Auth::user()->name . ". Anda dapat membuat jadwal ujian.",
            'success',
            route('mahasiswa.ujian.create'),
            Bimbingan::class,
            $bimbingan->id,
            Auth::id(),
            "Pembimbing: " . $bimbingan->dosen->name
        );

        return back()->with('success', 'Bimbingan berhasil ditandai selesai. Mahasiswa dapat membuat jadwal ujian.');
    }

    // Dosen: kembalikan bimbingan dari selesai ke disetujui
    public function markNotSelesai(Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }
        $bimbingan->update(['status' => 'disetujui', 'selesai_at' => null]);

        Notification::send(
            $bimbingan->mahasiswa_id,
            'Status Bimbingan Diubah',
            "Bimbingan " . Bimbingan::JENIS[$bimbingan->jenis_bimbingan] . " Anda dikembalikan ke status disetujui oleh " . Auth::user()->name . ". Mungkin masih ada yang perlu diperbaiki.",
            'warning',
            route('mahasiswa.bimbingan.show', $bimbingan->id),
            Bimbingan::class,
            $bimbingan->id,
            Auth::id(),
            "Pembimbing: " . $bimbingan->dosen->name
        );

        return back()->with('success', 'Bimbingan berhasil dikembalikan ke status disetujui.');
    }

    // Mahasiswa: riwayat
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $query = \App\Models\BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
            $q->where('mahasiswa_id', $user->id);
        })->with(['bimbingan' => function ($q) {
            $q->with('dosen');
        }]);

        // Filter by jenis bimbingan
        if ($request->has('jenis_bimbingan') && !empty($request->jenis_bimbingan)) {
            $query->whereHas('bimbingan', function ($q) use ($request) {
                $q->where('jenis_bimbingan', $request->jenis_bimbingan);
            });
        }

        $progressRecords = $query->latest()->get();
        $filterJenis = $request->get('jenis_bimbingan', '');

        return view('mahasiswa.bimbingan.riwayat', compact('progressRecords', 'filterJenis'));
    }

    // Check available jenis bimbingan for a mahasiswa
    public function getAvailableJenis(int $mahasiswaId): array
    {
        $order = Bimbingan::JENIS_ORDER;
        $available = [];

        foreach ($order as $jenis) {
            // Check if this jenis ujian is completed
            $hasSelesaiUjian = Ujian::where('mahasiswa_id', $mahasiswaId)
                ->where('jenis_ujian', $jenis)
                ->where('status', 'selesai')
                ->exists();

            if ($hasSelesaiUjian) {
                continue; // This stage is done, move to next
            }

            $available[] = $jenis;
            break; // Only one jenis at a time
        }

        return $available;
    }

    // Export riwayat bimbingan to PDF
    public function exportRiwayat(Request $request)
    {
        $user = Auth::user();

        $query = BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
            $q->where('mahasiswa_id', $user->id);
        })->with(['bimbingan' => function ($q) {
            $q->with('dosen');
        }]);

        // Filter by jenis bimbingan
        if ($request->has('jenis_bimbingan') && !empty($request->jenis_bimbingan)) {
            $query->whereHas('bimbingan', function ($q) use ($request) {
                $q->where('jenis_bimbingan', $request->jenis_bimbingan);
            });
        }

        $progressRecords = $query->latest()->get();
        $filterJenis = $request->get('jenis_bimbingan', '');

        $pdf = Pdf::loadView('mahasiswa.bimbingan.export-riwayat', compact('user', 'progressRecords', 'filterJenis'));
        return $pdf->stream('riwayat-bimbingan.pdf');
    }
}
