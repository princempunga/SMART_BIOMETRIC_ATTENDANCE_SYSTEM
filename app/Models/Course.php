<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['course_name', 'course_code', 'lecturer_id'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
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
