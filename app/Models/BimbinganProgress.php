<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbinganProgress extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_progresses';

    protected $fillable = [
        'bimbingan_id', 'tanggal_bimbingan', 'catatan', 'file_path', 'status', 'catatan_dosen', 'approved_at',
    ];

    protected $casts = [
        'tanggal_bimbingan' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class, 'bimbingan_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu' => '<span class="badge bg-warning text-dark">Menunggu Paraf</span>',
            'disetujui' => '<span class="badge bg-success">Sudah Diparaf</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
