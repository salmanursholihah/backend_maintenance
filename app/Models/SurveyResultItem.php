<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResultItem extends Model
{
    use HasFactory;
    protected $guarded = [];
      protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
        'qty'      => 'integer',
    ];
 
    // ─── Boot ─────────────────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($item) {
            $item->subtotal = $item->price * $item->qty;
        });
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak item milik 1 survey result
    public function surveyResult()
    {
        return $this->belongsTo(SurveyResult::class);
    }
 
    // banyak item merujuk ke 1 component (opsional)
    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
