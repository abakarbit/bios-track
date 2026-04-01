<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable as NotifiableTrait;

class User extends Authenticatable
{
    use HasFactory, NotifiableTrait;

    protected $fillable = [
        'name', 'email', 'username', 'password', 'role',
        'nim', 'nip', 'prodi', 'angkatan', 'phone', 'avatar', 'signature_path', 'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role helpers
    public function isMahasiswa(): bool { return $this->role === 'mahasiswa'; }
    public function isDosen(): bool { return $this->role === 'dosen'; }
    public function isKaprodi(): bool { return $this->role === 'kaprodi'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }

    // Relationships
    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class, 'mahasiswa_id');
    }

    public function bimbingansAsDosen()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }

    public function bimbinganProgresses()
    {
        return $this->hasMany(BimbinganProgress::class, 'mahasiswa_id');
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'mahasiswa_id');
    }

    public function ujianAsPembimbing1()
    {
        return $this->hasMany(Ujian::class, 'dosen_pembimbing1_id');
    }

    public function ujianAsPembimbing2()
    {
        return $this->hasMany(Ujian::class, 'dosen_pembimbing2_id');
    }

    public function ujianAsPenguji1()
    {
        return $this->hasMany(Ujian::class, 'dosen_penguji1_id');
    }

    public function ujianAsPenguji2()
    {
        return $this->hasMany(Ujian::class, 'dosen_penguji2_id');
    }

    public function appNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }
}
