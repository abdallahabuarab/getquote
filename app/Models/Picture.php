<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id', 'picture_name', 'picture_size', 'picture_location',
        'upload_longitude', 'upload_latitude', 'upload_time', 'uploaded_by', 'upload_ip'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
