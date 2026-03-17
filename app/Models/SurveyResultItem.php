<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResultItem extends Model
{
    use HasFactory;
        protected $guarded = [];

protected $casts = [
        'qty' => 'integer',
        'price' => 'float',
        'subtotal' => 'float',
    ];

    public function surveyResult()
    {
        return $this->belongsTo(SurveyResult::class);
    }
}
