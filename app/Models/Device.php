<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['classroom_id', 'device_code', 'device_api_token', 'status'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
