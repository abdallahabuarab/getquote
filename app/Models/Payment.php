<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id', 'payment_id', 'request_total', 'payment_method', 'brand',
        'payment_status', 'payment_account_last4', 'stripe_payment_method_id',
        'billing_address', 'method_exp_month', 'method_exp_year', 'payment_confirmation',
        'payment_created_at', 'payment_updated_at'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'payment_id', 'payment_id');
    }
}
