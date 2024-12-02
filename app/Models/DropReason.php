<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropReason extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['reason'];
    protected $primaryKey = 'reason_id';

    public function rejects()
    {
        return $this->hasMany(Reject::class, 'reject_reason', 'id');
    }
}
