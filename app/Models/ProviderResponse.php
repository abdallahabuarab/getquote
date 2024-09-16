<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderResponse extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id', 'provider_id', 'provider_response',
        'eta', 'reason_id', 'provider_response_time'
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
