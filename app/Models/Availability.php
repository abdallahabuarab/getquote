<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider_id', 'class_id', 'service_id', 'availability',
        'service_price', 'free_loaded_miles', 'free_enroute_miles',
        'loaded_mile_price', 'enroute_mile_price'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}