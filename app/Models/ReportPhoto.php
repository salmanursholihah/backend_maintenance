<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    use HasFactory;
        protected $guarded = [];

           // ─── Accessors ────────────────────────────────────────────────
    public function getPhotoUrlAttribute(): string
    {
        return asset('storage/' . $this->photo);
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak foto milik 1 maintenance report
    public function maintenanceReport()
    {
        return $this->belongsTo(MaintenanceReport::class, 'report_id');
    }
}
