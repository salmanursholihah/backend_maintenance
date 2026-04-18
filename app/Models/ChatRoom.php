<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    protected $guarded = [];

     // ─── Relationships ─────────────────────────────────────────────
     
    // ─── Helpers ──────────────────────────────────────────────────
 
    // hitung pesan yang belum dibaca oleh user tertentu
    public function unreadCount(int $userId): int
    {
        return $this->chatMessages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak room terkait 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak room milik 1 customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
 
    // banyak room milik 1 teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
 
    // 1 room punya banyak pesan
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
 
    // helper: ambil 1 pesan terakhir saja
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }
}
