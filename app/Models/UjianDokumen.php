<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UjianDokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id', 'mahasiswa_id', 'berkas_bap', 'berkas_nilai',
        'nilai', 'keterangan', 'uploaded_at',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'uploaded_at' => 'datetime',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
