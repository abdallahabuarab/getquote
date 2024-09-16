<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id', 'business_name', 'destination_street_number', 'destination_route',
        'destination_locality', 'destination_administrative_area_level_1', 'destination_zipcode',
        'destination_cross_street', 'destination_name', 'destination_location_type_id',
        'destination_longitude', 'destination_latitude', 'destination_address_source',
        'destination_note', 'destination_created_at', 'destination_updated_at'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function locationType()
    {
        return $this->belongsTo(LocationType::class, 'destination_location_type_id');
    }
}
