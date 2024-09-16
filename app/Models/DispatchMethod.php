<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchMethod extends Model
{
    use HasFactory;
    protected $fillable = ['dispatch_method'];

    public function providers()
    {
        return $this->hasMany(Provider::class, 'dispatch_method', 'id');
    }
}
