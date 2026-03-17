<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLocation extends Model
{
    use HasFactory;
        protected $guarded = [];

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
