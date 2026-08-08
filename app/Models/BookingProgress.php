<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingProgress extends Model
{
    use HasFactory;

    // eksplisit ditulis di HEAD; sebenarnya sama dengan default konvensi Laravel,
    // tapi dipertahankan biar sesuai niat aslinya
    protected $table = 'booking_progresses';

    protected $guarded = [];

    protected $casts = [
        'progress_percent' => 'integer',
        'progress_at'      => 'datetime',
    ];

    // ─── Accessors (dari remote) ────────────────────────────────────
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : null;
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
