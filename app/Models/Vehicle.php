<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id', 'vehicle_year', 'vehicle_make', 'vehicle_model',
        'vehicle_color', 'vehicle_style', 'drive_train', 'VIN', 'Plate'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
