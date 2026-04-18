<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingProgress extends Model
{
    use HasFactory;
        protected $guarded = [];

           protected $casts = [
        'progress_percent' => 'integer',
        'progress_at'      => 'datetime',
    ];
 
    // ─── Accessors ────────────────────────────────────────────────
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : null;
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak progress milik 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak progress dibuat oleh 1 teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
