<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = ['course_unit_id', 'classroom_id', 'day_of_week', 'start_time', 'end_time'];

    public function course()
    {
        return $this->belongsTo(CourseUnit::class, 'course_unit_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
