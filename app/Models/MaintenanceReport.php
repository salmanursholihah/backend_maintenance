<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MaintenanceReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'work_duration' => 'integer',
        'reported_at'   => 'datetime',
    ];

    // ─── Condition Constants (dari remote) ─────────────────────────
    const CONDITION_GOOD           = 'good';
    const CONDITION_NEED_ATTENTION = 'need_attention';
    const CONDITION_CRITICAL       = 'critical';

    // ─── Relationships ─────────────────────────────────────────────
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // PERHATIAN: cek manual method ini. Di file conflict aslinya,
    // HEAD punya method "photos(): HasMany" dan remote punya method
    // "technician()", tapi keduanya berbagi body penutup yang sama
    // (return hasMany(ReportPhoto::class)) — ini kemungkinan besar
    // adalah kesalahan diff/marker, bukan konflik asli. Body technician()
    // seharusnya "belongsTo(User::class, 'technician_id')", bukan hasMany(ReportPhoto).
    // Aku perbaiki jadi versi yang logikanya benar di bawah ini —
    // tolong cek ulang ke controller yang manggil method ini.
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
