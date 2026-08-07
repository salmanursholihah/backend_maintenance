<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'technician_id',
        'bank_account_id',
        'amount',
        'admin_fee',
        'received_amount',
        'status',
        'note',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(TechnicianBankAccount::class, 'bank_account_id');
    }
}


