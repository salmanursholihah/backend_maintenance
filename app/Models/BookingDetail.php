<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    // pakai $guarded (bukan $fillable) biar konsisten dengan model lain
    protected $guarded = [];

    protected $casts = [
        'price'    => 'decimal:2',
        'qty'      => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // ─── Boot ─────────────────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($detail) {
            $detail->subtotal = $detail->price * $detail->qty;
        });
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
