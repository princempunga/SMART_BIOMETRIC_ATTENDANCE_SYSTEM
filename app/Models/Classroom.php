<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['room_name', 'device_id'];

    public function sessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }
}
