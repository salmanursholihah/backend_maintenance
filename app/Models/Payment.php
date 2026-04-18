<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

      protected $casts = [
        'amount'           => 'decimal:2',
        'payment_metadata' => 'array',
        'paid_at'          => 'datetime',
        'expired_at'       => 'datetime',
    ];
 
    // ─── Status Constants ─────────────────────────────────────────
    const STATUS_PENDING  = 'pending';
    const STATUS_PAID     = 'paid';
    const STATUS_FAILED   = 'failed';
    const STATUS_EXPIRED  = 'expired';
    const STATUS_REFUNDED = 'refunded';
 
    // ─── Helpers ──────────────────────────────────────────────────
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
 
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak payment milik 1 booking
    // (normalnya 1 booking = 1 payment, tapi pakai hasMany agar retry bayar bisa)
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
