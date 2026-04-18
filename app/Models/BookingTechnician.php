<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTechnician extends Model
{
    use HasFactory;
    protected $guarded = [];
      protected $casts = [
        'is_lead'      => 'boolean',
        'assigned_at'  => 'datetime',
        'responded_at' => 'datetime',
    ];
 
    // ─── Status Constants ─────────────────────────────────────────
    const STATUS_ASSIGNED  = 'assigned';
    const STATUS_ACCEPTED  = 'accepted';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_WORKING   = 'working';
    const STATUS_COMPLETED = 'completed';
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak penugasan milik 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak penugasan merujuk ke 1 user (teknisi)
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
