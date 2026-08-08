<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'estimated_duration'   => 'integer',
        'service_cost'         => 'decimal:2',
        'sparepart_cost'       => 'decimal:2',
        'other_cost'           => 'decimal:2',
        'estimated_total_cost' => 'decimal:2',
        'submitted_at'         => 'datetime',
        'approved_at'          => 'datetime',
        'rejected_at'          => 'datetime',
    ];

    // ─── Status Constants (dari remote) ────────────────────────────
    const STATUS_DRAFT     = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';

    // ─── Helpers (dari remote) ──────────────────────────────────────
    public function recalculateTotal(): void
    {
        $this->estimated_total_cost =
            $this->service_cost + $this->sparepart_cost + $this->other_cost;
        $this->save();
    }

    // ─── Relationships (nama & type hint dari HEAD) ─────────────────
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SurveyResultItem::class);
    }

    // alias biar kompatibel kalau ada kode lain yang manggil nama remote
    public function surveyResultItems(): HasMany
    {
        return $this->items();
    }
}
