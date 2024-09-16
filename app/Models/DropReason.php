<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropReason extends Model
{
    use HasFactory;
    protected $fillable = ['reason'];

    public function rejects()
    {
        return $this->hasMany(Reject::class, 'reject_reason', 'id');
    }
}
