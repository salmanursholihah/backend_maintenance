<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    protected $casts = [
        'maintenance_date' => 'date',
    ];
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak history milik 1 lokasi
    public function maintenanceLocation()
    {
        return $this->belongsTo(MaintenanceLocation::class, 'location_id');
    }
 
    // 1 history berasal dari 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
