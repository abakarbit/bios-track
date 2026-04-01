<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\BimbinganProgress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BimbinganProgressController extends Controller
{
    public function create(Bimbingan $bimbingan)
    {
        $user = Auth::user();
        if ($bimbingan->mahasiswa_id !== $user->id) abort(403);
        if ($bimbingan->status !== 'disetujui') {
            return back()->with('error', 'Bimbingan harus disetujui dosen sebelum menambah progress.');
        }
        return view('mahasiswa.bimbingan.progress.create', compact('bimbingan'));
    }

    public function store(Request $request, Bimbingan $bimbingan)
    {
        $user = Auth::user();
        if ($bimbingan->mahasiswa_id !== $user->id) abort(403);
        if ($bimbingan->status !== 'disetujui') abort(400, 'Bimbingan belum disetujui.');

        $validated = $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'catatan'   => 'required|string|max:2000',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('bimbingan-progress', 'public');
        }

        $progress = BimbinganProgress::create([
            'bimbingan_id' => $bimbingan->id,
            'tanggal_bimbingan' => $validated['tanggal_bimbingan'],
            'catatan'      => $validated['catatan'],
            'file_path'    => $filePath,
            'status'       => 'menunggu',
        ]);

        Notification::send(
            $bimbingan->dosen_id,
            'Update Progress Bimbingan',
            "Mahasiswa {$user->name} menambahkan progress bimbingan. Silakan berikan paraf.",
            'info',
            route('dosen.bimbingan.show', $bimbingan->id),
            BimbinganProgress::class,
            $progress->id,
            $user->id,
            "Tanggal: {$validated['tanggal_bimbingan']}. Catatan: {$validated['catatan']}"
        );

        return redirect()->route('mahasiswa.bimbingan.show', $bimbingan->id)
            ->with('success', 'Progress bimbingan berhasil ditambahkan. Menunggu paraf dosen.');
    }

    public function approve(Request $request, BimbinganProgress $progress)
    {
        $user = Auth::user();
        $bimbingan = $progress->bimbingan;
        if ($bimbingan->dosen_id !== $user->id) abort(403);

        $request->validate([
            'action'       => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'nullable|string|max:500',
        ]);

        $progress->update([
            'status'       => $request->action,
            'catatan_dosen'=> $request->catatan_dosen,
            'approved_at'  => $request->action === 'disetujui' ? now() : null,
        ]);

        $statusLabel = $request->action === 'disetujui' ? 'diparaf' : 'ditolak';

        Notification::send(
            $bimbingan->mahasiswa_id,
            'Progress Bimbingan Diperbarui',
            "Progress bimbingan Anda telah {$statusLabel} oleh " . $user->name,
            $request->action === 'disetujui' ? 'success' : 'danger',
            route('mahasiswa.bimbingan.show', $bimbingan->id),
            BimbinganProgress::class,
            $progress->id,
            $user->id,
            "Dari: {$user->name}. Catatan: {$request->catatan_dosen}"
        );

        return back()->with('success', "Progress berhasil {$statusLabel}.");
    }

    public function edit(BimbinganProgress $progress)
    {
        $user = Auth::user();
        $bimbingan = $progress->bimbingan;

        // Only mahasiswa can edit their own progress, and only if status is 'menunggu'
        if ($bimbingan->mahasiswa_id !== $user->id) abort(403);
        if ($progress->status !== 'menunggu') {
            return back()->with('error', 'Hanya progress yang belum diparaf yang bisa diedit.');
        }

        return view('mahasiswa.bimbingan.progress.edit', compact('progress', 'bimbingan'));
    }

    public function update(Request $request, BimbinganProgress $progress)
    {
        $user = Auth::user();
        $bimbingan = $progress->bimbingan;

        // Only mahasiswa can update their own progress, and only if status is 'menunggu'
        if ($bimbingan->mahasiswa_id !== $user->id) abort(403);
        if ($progress->status !== 'menunggu') {
            return back()->with('error', 'Hanya progress yang belum diparaf yang bisa diedit.');
        }

        $validated = $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'catatan'   => 'required|string|max:2000',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Handle file update
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($progress->file_path) {
                Storage::disk('public')->delete($progress->file_path);
            }
            $filePath = $request->file('file_path')->store('bimbingan-progress', 'public');
            $validated['file_path'] = $filePath;
        }

        $progress->update($validated);

        Notification::send(
            $bimbingan->dosen_id,
            'Update Progress Bimbingan Direvisi',
            "Mahasiswa {$user->name} merevisi progress bimbingan. Silakan berikan paraf.",
            'info',
            route('dosen.bimbingan.show', $bimbingan->id),
            BimbinganProgress::class,
            $progress->id,
            $user->id,
            "Tanggal: {$validated['tanggal_bimbingan']}. Catatan: {$validated['catatan']}"
        );

        return redirect()->route('mahasiswa.bimbingan.show', $bimbingan->id)
            ->with('success', 'Progress bimbingan berhasil diperbarui.');
    }
}
