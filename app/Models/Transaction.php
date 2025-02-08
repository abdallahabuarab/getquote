<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id', 'payment_amount', 'payment_method', 'payment_status',
        'stripe_charge_id', 'currency', 'transaction_confirmation',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
