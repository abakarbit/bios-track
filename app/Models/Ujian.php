<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id', 'jenis_ujian', 'tanggal_ujian', 'tempat_ujian',
        'dosen_pembimbing1_id', 'dosen_pembimbing2_id',
        'dosen_penguji1_id', 'dosen_penguji2_id',
        'status_pembimbing1', 'status_pembimbing2', 'status_penguji1', 'status_penguji2',
        'status_kaprodi', 'status', 'catatan_kaprodi', 'approved_kaprodi_at',
    ];

    protected $casts = [
        'tanggal_ujian' => 'datetime',
        'approved_kaprodi_at' => 'datetime',
    ];

    const JENIS = [
        'proposal' => 'Ujian Proposal',
        'seminar_hasil' => 'Seminar Hasil',
        'laporan_skripsi' => 'Ujian Skripsi (Sidang)',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function pembimbing1()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing1_id');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing2_id');
    }

    public function penguji1()
    {
        return $this->belongsTo(User::class, 'dosen_penguji1_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(User::class, 'dosen_penguji2_id');
    }

    public function dokumen()
    {
        return $this->hasOne(UjianDokumen::class, 'ujian_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis_ujian] ?? $this->jenis_ujian;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu' => '<span class="badge bg-warning text-dark">Menunggu Persetujuan Dosen</span>',
            'disetujui_dosen' => '<span class="badge bg-info">Disetujui Dosen - Menunggu Kaprodi</span>',
            'disetujui_kaprodi' => '<span class="badge bg-success">Disetujui Kaprodi</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'selesai' => '<span class="badge bg-primary">Selesai</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function isAllPembimbingApproved(): bool
    {
        $pembimbing1Ok = $this->status_pembimbing1 === 'disetujui';
        $pembimbing2Ok = ($this->dosen_pembimbing2_id === null) || ($this->status_pembimbing2 === 'disetujui');
        return $pembimbing1Ok && $pembimbing2Ok;
    }
}
