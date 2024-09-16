<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id', 'inside_garage', 'garage_clearance', 'garage_floor', 'dangerous_location',
        'vehicle_starts', 'flat_tire', 'tires_flat', 'missing_wheels', 'wheels_missing',
        'broken_axle', 'key_present', 'customer_notes', 'provider_notes'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
