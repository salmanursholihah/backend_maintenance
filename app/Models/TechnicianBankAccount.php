<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianBankAccount extends Model
{
    protected $fillable = [
        'technician_id',
        'type',
        'provider',
        'account_number',
        'account_name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}


