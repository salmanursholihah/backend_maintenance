<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTechnician extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_lead'      => 'boolean',
        'assigned_at'  => 'datetime',
        'responded_at' => 'datetime',
    ];

    // ─── Status Constants (dari remote) ────────────────────────────
    const STATUS_ASSIGNED  = 'assigned';
    const STATUS_ACCEPTED  = 'accepted';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_WORKING   = 'working';
    const STATUS_COMPLETED = 'completed';

    // ─── Relationships (type hint dari HEAD) ────────────────────────
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
