<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipcodeReference extends Model
{
    use HasFactory;
    protected $table = 'zipcode_reference';
    public function zipcodeCoverages()
    {
        return $this->hasMany(ZipcodeCoverage::class);
    }
    public function providers()
{
    return $this->belongsToMany(Provider::class, 'zipcode_coverage', 'zipcode', 'provider_id')
                ->withPivot('rank'); // Include rank as pivot data
}

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
    public function rejects()
    {
        return $this->hasMany(Reject::class);
    }
    public function scopeSearch($query, $term)
{
    return $query->where('zipcode', 'LIKE', "%{$term}%");
}
}
