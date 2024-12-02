<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassName extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'classes';
    protected $primaryKey = 'class_id';

    protected $fillable = ['name'];

    public function requests()
    {
        return $this->hasMany(Request::class, 'request_class', 'class_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'class_id', 'id');
    }

    public function rejects()
    {
        return $this->hasMany(Reject::class, 'reject_class', 'id');
    }
}
