<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceReport extends Model
{
    use HasFactory;

        protected $guarded = [];
    
    protected $casts = [
        'work_duration' => 'integer',
        'reported_at'   => 'datetime',
    ];
 
    // ─── Condition Constants ──────────────────────────────────────
    const CONDITION_GOOD           = 'good';
    const CONDITION_NEED_ATTENTION = 'need_attention';
    const CONDITION_CRITICAL       = 'critical';
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak laporan milik 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak laporan dibuat oleh 1 teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
 
    // 1 laporan punya banyak foto
    // (foto dipisah ke tabel report_photos — model ReportPhoto)
    public function reportPhotos()
    {
        return $this->hasMany(ReportPhoto::class, 'report_id');
    }
 
    // helper: foto sebelum pengerjaan saja
    public function beforePhotos()
    {
        return $this->hasMany(ReportPhoto::class, 'report_id')
            ->where('type', 'before');
    }
 
    // helper: foto sesudah pengerjaan saja
    public function afterPhotos()
    {
        return $this->hasMany(ReportPhoto::class, 'report_id')
            ->where('type', 'after');
    }
 
    // helper: foto dokumentasi saja
    public function documentationPhotos()
    {
        return $this->hasMany(ReportPhoto::class, 'report_id')
            ->where('type', 'documentation');
    }
}
