<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    protected $guarded = [];

     protected $casts = [
        'default_price' => 'decimal:2',
        'is_active'     => 'boolean',
    ];
 
    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
 
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // 1 component dipakai di banyak survey result item
    public function surveyResultItems()
    {
        return $this->hasMany(SurveyResultItem::class);
    }
}
