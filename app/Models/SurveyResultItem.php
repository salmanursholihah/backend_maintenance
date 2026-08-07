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

    public function surveyResult(): BelongsTo
    {
        return $this->belongsTo(SurveyResult::class);
    }
}




