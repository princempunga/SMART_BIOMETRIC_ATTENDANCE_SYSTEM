<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'room_code',
        'building_name',
        'floor_number',
        'seating_capacity',
        'status',
    ];

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function sessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
