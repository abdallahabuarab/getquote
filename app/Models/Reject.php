<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reject extends Model
{

    use HasFactory;
    protected $fillable = [
        'reject_ip_address', 'reject_ip_city', 'reject_ip_region', 'reject_ip_country',
        'reject_ip_timezone', 'reject_device', 'reject_os', 'reject_street_number',
        'reject_route', 'reject_locality', 'reject_administrative_area_level_1', 'reject_zipcode',
        'reject_cross_street', 'reject_location_name', 'reject_location_type_id', 'reject_longitude',
        'reject_latitude', 'reject_class', 'reject_service', 'reject_datetime', 'reject_address_source',
        'reject_reason', 'reject_created_at', 'reject_updated_at', 'request_id'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function reason()
    {
        return $this->belongsTo(DropReason::class, 'reject_reason');
    }

    public function locationType()
    {
        return $this->belongsTo(LocationType::class, 'reject_location_type_id');
    }
    public function classModel()
{
    return $this->belongsTo(ClassName::class, 'reject_class', 'class_id');
}

public function service()
{
    return $this->belongsTo(Service::class, 'reject_service', 'service_id');
}

}
