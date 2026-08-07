<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function maintenancereports()
    {
        return $this->belongsTo(MaintenanceReport::class);
    }
}
