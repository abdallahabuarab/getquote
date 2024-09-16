<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    use HasFactory;
    protected $table = 'location_types';
    protected $fillable = ['location_type'];

    public function requests()
    {
        return $this->hasMany(Request::class, 'request_location_type_id', 'id');
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class, 'destination_location_type_id', 'id');
    }

    public function rejects()
    {
        return $this->hasMany(Reject::class, 'reject_location_type_id', 'id');
    }
}
