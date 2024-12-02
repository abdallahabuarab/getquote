<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'service_id';
    use HasFactory;
    protected $fillable = ['name'];

    public function requests()
    {
        return $this->hasMany(Request::class, 'request_service', 'service_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'service_id', 'id');
    }

    public function rejects()
    {
        return $this->hasMany(Reject::class, 'reject_service', 'id');
    }
}
