<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;
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
 
    // banyak detail milik 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak detail merujuk ke 1 service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
