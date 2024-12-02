<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderSchedule extends Model
{
    protected $primaryKey = null;
public $incrementing = false;
    public $timestamps = false;

    use HasFactory;
    protected $fillable = [
        'provider_id', 'dayofweek', 'start_time', 'close_time', 'open_day'
    ];


public function provider()
{
    return $this->belongsTo(Provider::class, 'provider_id');
}

    public function weekday()
    {
        return $this->belongsTo(Weekday::class, 'dayofweek','dayofweek');
    }
}
