<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded = [];

      protected $casts = [
        'rating' => 'integer',
    ];
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak review milik 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak review dibuat oleh 1 customer
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    // banyak review ditujukan ke 1 teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
