<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    use HasFactory;
    protected $guarded = [];
      protected $casts = [
        'service_cost'         => 'decimal:2',
        'sparepart_cost'       => 'decimal:2',
        'other_cost'           => 'decimal:2',
        'estimated_total_cost' => 'decimal:2',
        'estimated_duration'   => 'integer',
        'submitted_at'         => 'datetime',
        'approved_at'          => 'datetime',
        'rejected_at'          => 'datetime',
    ];
 
    // ─── Status Constants ─────────────────────────────────────────
    const STATUS_DRAFT     = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
 
    // ─── Helpers ──────────────────────────────────────────────────
    public function recalculateTotal(): void
    {
        $this->estimated_total_cost =
            $this->service_cost + $this->sparepart_cost + $this->other_cost;
        $this->save();
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak survey milik 1 booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
 
    // banyak survey dibuat oleh 1 teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
 
    // 1 survey punya banyak item (material, sparepart, dll)
    public function surveyResultItems()
    {
        return $this->hasMany(SurveyResultItem::class);
    }
}
