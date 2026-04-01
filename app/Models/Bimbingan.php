<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id', 'dosen_id', 'jenis_bimbingan', 'tanggal_bimbingan', 'pembimbing',
        'topik', 'catatan_mahasiswa', 'status', 'catatan_dosen', 'catatan_kaprodi',
        'approved_at', 'selesai_at',
    ];

    protected $casts = [
        'tanggal_bimbingan' => 'date',
        'approved_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    const JENIS = [
        'proposal' => 'Proposal',
        'seminar_hasil' => 'Seminar Hasil',
        'laporan_skripsi' => 'Ujian Sidang Akhir',
    ];

    const JENIS_ORDER = ['proposal', 'seminar_hasil', 'laporan_skripsi'];



    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function progresses()
    {
        return $this->hasMany(BimbinganProgress::class, 'bimbingan_id')->orderBy('created_at', 'asc');
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis_bimbingan] ?? $this->jenis_bimbingan;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu' => '<span class="badge bg-warning text-dark">Menunggu Persetujuan</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'selesai' => '<span class="badge bg-primary">Selesai</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
