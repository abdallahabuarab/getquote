<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider_name', 'provider_address', 'provider_city', 'provider_state',
        'zipcode', 'provider_phone', 'provider_fax', 'provider_email', 'contact_name',
        'contact_phone', 'is_active', 'weekend_m', 'holiday_m', 'evening_m',
        'dispatch_method', 'request_processing', 'payment_distribution'
    ];
    public function zipcodeCoverages()
    {
        return $this->hasMany(ZipcodeCoverage::class);
    }
    public function providerResponses()
    {
        return $this->hasMany(ProviderResponse::class);
    }
    public function credential()
    {
        return $this->hasOne(ProviderCredential::class);
    }
    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
    public function schedules()
    {
        return $this->hasMany(ProviderSchedule::class);
    }
    public function dispatchMethod()
    {
        return $this->belongsTo(DispatchMethod::class, 'dispatch_method');
    }

    public function paymentDistribution()
    {
        return $this->belongsTo(PaymentDistribution::class, 'payment_distribution');
    }
}
