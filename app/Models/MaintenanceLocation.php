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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'location_id');
    }

    public function maintenanceHistories()
    {
        return $this->hasMany(MaintenanceHistory::class, 'location_id');
    }
}
