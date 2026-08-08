<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ─── Helpers (dari remote) ──────────────────────────────────────
    // hitung pesan yang belum dibaca oleh user tertentu
    public function unreadCount(int $userId): int
    {
        return $this->chatMessages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_room_id');
    }

    // helper: ambil 1 pesan terakhir saja
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }
}
