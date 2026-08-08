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

    // ─── Accessors (dari remote) ────────────────────────────────────
    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment
            ? asset('storage/' . $this->attachment)
            : null;
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id'); // adjust FK column name if different
    }
}
