<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipcodeCoverage extends Model
{
    use HasFactory;
    protected $fillable = ['zipcode', 'rank', 'provider_id'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function zipcodeReference()
    {
        return $this->belongsTo(ZipcodeReference::class, 'zipcode', 'zipcode');
    }
}
