<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'sender_id', 'title', 'message', 'description', 'type', 'url',
        'notifiable_type', 'notifiable_id', 'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public static function send(int $userId, string $title, string $message, string $type = 'info', ?string $url = null, ?string $notifiableType = null, ?int $notifiableId = null, ?int $senderId = null, ?string $description = null): self
    {
        return self::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'title' => $title,
            'message' => $message,
            'description' => $description,
            'type' => $type,
            'url' => $url,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'is_read' => false,
        ]);
    }
}
