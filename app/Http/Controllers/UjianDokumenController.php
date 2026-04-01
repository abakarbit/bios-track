<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Ujian;
use App\Models\UjianDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UjianDokumenController extends Controller
{
    public function create(Ujian $ujian)
    {
        $user = Auth::user();
        if ($ujian->mahasiswa_id !== $user->id) abort(403);
        if ($ujian->status !== 'disetujui_kaprodi') {
            return back()->with('error', 'Ujian harus disetujui Kaprodi sebelum upload dokumen.');
        }
        if ($ujian->dokumen) {
            return back()->with('info', 'Dokumen sudah diupload sebelumnya.');
        }
        return view('mahasiswa.ujian.dokumen.create', compact('ujian'));
    }

    public function store(Request $request, Ujian $ujian)
    {
        $user = Auth::user();
        if ($ujian->mahasiswa_id !== $user->id) abort(403);
        if ($ujian->status !== 'disetujui_kaprodi') abort(400);

        $request->validate([
            'berkas_bap' => 'required|file|mimes:pdf|max:5120',
            'berkas_nilai' => 'nullable|file|mimes:pdf|max:5120',
            'nilai' => 'nullable',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $bapPath = $request->file('berkas_bap')->store('ujian-dokumen', 'public');
        $nilaiPath = null;
        if ($request->hasFile('berkas_nilai')) {
            $nilaiPath = $request->file('berkas_nilai')->store('ujian-dokumen', 'public');
        }

        $dokumen = UjianDokumen::create([
            'ujian_id' => $ujian->id,
            'mahasiswa_id' => $user->id,
            'berkas_bap' => $bapPath,
            'berkas_nilai' => $nilaiPath,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'uploaded_at' => now(),
        ]);

        // Mark ujian as selesai
        $ujian->update(['status' => 'selesai']);

        // Notify involved dosen and kaprodi
        $recipients = collect([
            $ujian->dosen_pembimbing1_id, $ujian->dosen_pembimbing2_id,
            $ujian->dosen_penguji1_id, $ujian->dosen_penguji2_id,
        ])->filter()->unique();

        foreach ($recipients as $dosenId) {
            Notification::send($dosenId, 'Dokumen Ujian Diupload',
                "{$user->name} telah mengupload BAP ujian " . Ujian::JENIS[$ujian->jenis_ujian],
                'success', null, Ujian::class, $ujian->id, $user->id, "Nilai: {$request->nilai}. Keterangan: {$request->keterangan}");
        }

        return redirect()->route('mahasiswa.ujian.show', $ujian->id)
            ->with('success', 'Dokumen ujian berhasil diupload. Ujian dinyatakan selesai!');
    }
}
