<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['room_name'];

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function sessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }
}
