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

    public function location()
    {
        return $this->belongsTo(MaintenanceLocation::class, 'location_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
