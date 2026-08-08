<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ─── Accessors (dari remote) ────────────────────────────────────
    public function getPhotoUrlAttribute(): string
    {
        return asset('storage/' . $this->photo);
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function maintenanceReport()
    {
        return $this->belongsTo(MaintenanceReport::class);
    }
}
