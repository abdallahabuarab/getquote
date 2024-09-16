<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDistribution extends Model
{
    use HasFactory;
    protected $fillable = ['payment_distribution'];

    public function providers()
    {
        return $this->hasMany(Provider::class, 'payment_distribution', 'id');
    }
}
