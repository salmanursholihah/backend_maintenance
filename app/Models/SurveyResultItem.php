<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResultItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'qty'      => 'integer',
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ─── Boot (dari remote) ─────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($item) {
            $item->subtotal = $item->price * $item->qty;
        });
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function surveyResult(): BelongsTo
    {
        return $this->belongsTo(SurveyResult::class);
    }

    // banyak item merujuk ke 1 component (opsional)
    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
