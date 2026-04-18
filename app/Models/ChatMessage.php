<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $guarded = [];
 protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];
 
    // ─── Accessors ────────────────────────────────────────────────
    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment
            ? asset('storage/' . $this->attachment)
            : null;
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak pesan milik 1 room
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
 
    // banyak pesan dikirim oleh 1 user
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
