<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderResponse extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'request_id', 'provider_id', 'provider_respose',
        'eta', 'reason', 'provider_response_time'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function reason()
    {
        return $this->belongsTo(DropReason::class, 'reason_id');
    }
}
