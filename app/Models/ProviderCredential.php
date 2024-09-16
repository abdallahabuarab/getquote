<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCredential extends Model
{
    use HasFactory;
    protected $fillable = ['provider_id', 'login_name', 'password'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
