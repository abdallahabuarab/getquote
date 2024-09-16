<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $primaryKey = 'request_id';
    use HasFactory;
    protected $fillable = [
        'request_ip_address',
        'request_ip_city',
        'request_ip_region',
        'request_ip_country',
        'request_ip_timezone',
        'request_device',
        'request_os',
        'request_street_number',
        'request_route',
        'request_locality',
        'request_administrative_area_level_1',
        'request_zipcode',
        'request_cross_street',
        'request_location_name',
        'request_location_type_id',
        'request_longitude',
        'request_latitude',
        'request_class',
        'request_service',
        'request_datetime',
        'request_priority',
        'request_address_source'
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
    public function vehicle()
    {
        return $this->hasOne(Vehicle::class,'request_id', 'request_id');
    }
    public function destinations()
    {
        return $this->hasOne(Destination::class);
    }
    public function providerResponses()
    {
        return $this->hasOne(ProviderResponse::class);
    }
    public function payments()
    {
        return $this->hasOne(Payment::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'request_service');
    }

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'request_class');
    }
    public function locationType()
    {
        return $this->belongsTo(LocationType::class, 'request_location_type_id', 'location_type_id');  // Use correct foreign and local keys
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
    public function cases()
    {
        return $this->hasOne(CaseModel::class);
    }

}
