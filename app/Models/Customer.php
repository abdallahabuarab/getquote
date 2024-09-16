<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['request_id', 'given_name', 'surname', 'email', 'phone_number', 'other_phone_number', 'communication_preference'];

    use HasFactory;
    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
