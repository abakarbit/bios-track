<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\BimbinganProgress;
use App\Models\Ujian;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function mahasiswa()
    {
        $user = Auth::user();
        $bimbingans = Bimbingan::where('mahasiswa_id', $user->id)
            ->with('dosen')
            ->latest()
            ->get();

        $ujians = Ujian::where('mahasiswa_id', $user->id)
            ->with(['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2'])
            ->latest()
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'bimbingan_total' => $bimbingans->count(),
            'bimbingan_disetujui' => $bimbingans->where('status', 'disetujui')->count(),
            'bimbingan_menunggu' => $bimbingans->where('status', 'menunggu')->count(),
            'progress_menunggu_paraf' => BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
                $q->where('mahasiswa_id', $user->id);
            })->where('status', 'menunggu')
            ->with('bimbingan.mahasiswa')
            ->count(),
            'ujian_total' => $ujians->count(),
            'ujian_selesai' => $ujians->where('status', 'selesai')->count(),
        ];

        // Progres bimbingan - untuk menampilkan catatan dan paraf dosen
        $progressRecords = BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
            $q->where('mahasiswa_id', $user->id);
        })->with(['bimbingan' => function ($q) {
            $q->with('dosen');
        }])->latest()->get();



        // Determine current stage
        $currentStage = $this->getCurrentStage($user->id);

       return view('mahasiswa.dashboard', compact('bimbingans', 'ujians', 'notifications', 'stats', 'currentStage', 'progressRecords'));
    }

    public function dosen()
    {
        $user = Auth::user();
        $bimbingans = Bimbingan::where('dosen_id', $user->id)
            ->with('mahasiswa')
            ->latest()
            ->get();

        $ujians = Ujian::where(function ($q) use ($user) {
                $q->where('dosen_pembimbing1_id', $user->id)
                    ->orWhere('dosen_pembimbing2_id', $user->id)
                    ->orWhere('dosen_penguji1_id', $user->id)
                    ->orWhere('dosen_penguji2_id', $user->id);
            })
            ->with('mahasiswa')
            ->latest()
            ->get();

        $pendingBimbingan = $bimbingans->where('status', 'menunggu')->count();
        $bimbinganSelesai = $bimbingans->where('status', 'selesai')->count();
        $bimbinganBelumSelesai = $bimbingans->whereNotIn('status', ['selesai', 'menunggu'])->count();
        $pendingProgress = BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
                $q->where('dosen_id', $user->id);
            })->where('status', 'menunggu')->count();
        $pendingUjian = $ujians->whereIn('status', ['menunggu', 'disetujui_pembimbing1'])->count();

        $totalMahasiswa = Bimbingan::where('dosen_id', $user->id)
            ->select('mahasiswa_id')
            ->distinct('mahasiswa_id')
            ->count();

        $recentPendingBimbingan = $bimbingans->where('status', 'menunggu')->take(5)->values();
        $recentPendingProgress = BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
                $q->where('dosen_id', $user->id);
            })->where('status', 'menunggu')
            ->with('bimbingan.mahasiswa')
            ->latest()->take(5)->get();
        $recentPendingUjian = $ujians->whereIn('status', ['menunggu', 'disetujui_pembimbing1'])
            ->take(5)->values();

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'bimbingans', 'ujians', 'notifications',
            'totalMahasiswa', 'pendingBimbingan', 'bimbinganSelesai', 'bimbinganBelumSelesai', 'pendingProgress', 'pendingUjian',
            'recentPendingBimbingan', 'recentPendingProgress', 'recentPendingUjian'
        ));
    }

    public function kaprodi()
    {
        $user = Auth::user();
        $allMahasiswa = User::where('role', 'mahasiswa')->with('bimbingans', 'ujians')->get();
        $allBimbingans = Bimbingan::with(['mahasiswa', 'dosen'])->latest()->take(10)->get();
        $allUjians = Ujian::with(['mahasiswa', 'pembimbing1'])->latest()->take(10)->get();
        $pendingUjians = Ujian::where('status', 'disetujui_dosen')->get();

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $totalMahasiswa = $allMahasiswa->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $pendingUjian = $pendingUjians->count();
        $ujianSelesai = Ujian::where('status', 'selesai')->count();
        $pendingUjianList = $pendingUjians->take(5);
        $recentMahasiswa = $allMahasiswa->take(5);

        return view('kaprodi.dashboard', compact(
            'allBimbingans', 'allUjians', 'notifications', 'allMahasiswa',
            'totalMahasiswa', 'totalDosen', 'pendingUjian', 'ujianSelesai', 'pendingUjianList', 'recentMahasiswa'
        ));
    }

    private function getCurrentStage(int $mahasiswaId): string
    {
        $order = ['proposal', 'seminar_hasil', 'laporan_skripsi'];

        foreach ($order as $jenis) {
            $hasSelesaiUjian = Ujian::where('mahasiswa_id', $mahasiswaId)
                ->where('jenis_ujian', $jenis)
                ->where('status', 'selesai')
                ->exists();

            if (!$hasSelesaiUjian) {
                return $jenis;
            }
        }

        return 'selesai';
    }

    public function exportPDF()
    {
        $user = Auth::user();
        $progressRecords = BimbinganProgress::whereHas('bimbingan', function ($q) use ($user) {
            $q->where('mahasiswa_id', $user->id);
        })->with(['bimbingan' => function ($q) {
            $q->with('dosen');
        }])->latest()->get();
        $currentStage = $this->getCurrentStage($user->id);

        $pdf = Pdf::loadView('mahasiswa.kartu-kendali', compact('user', 'progressRecords', 'currentStage'));
        return $pdf->stream('kartu-kendali.pdf');
    }
}
