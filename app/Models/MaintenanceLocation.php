<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLocation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'next_maintenance_date'     => 'date',
        'maintenance_interval_days' => 'integer',
        'is_active'                 => 'boolean',
    ];
 
    // ─── Helpers ──────────────────────────────────────────────────
    public function isDueForMaintenance(): bool
    {
        if (!$this->next_maintenance_date) return false;
 
        return $this->next_maintenance_date->isPast()
            || $this->next_maintenance_date->isToday();
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak lokasi milik 1 user (customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    // 1 lokasi punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'location_id');
    }
 
    // 1 lokasi punya banyak riwayat maintenance
    public function maintenanceHistories()
    {
        return $this->hasMany(MaintenanceHistory::class, 'location_id');
    }
}
