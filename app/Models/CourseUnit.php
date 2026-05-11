<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseUnit extends Model
{
    protected $fillable = ['course_code', 'course_name', 'faculty_id', 'department_id'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lecturers()
    {
        return $this->belongsToMany(User::class, 'course_unit_user', 'course_unit_id', 'user_id')->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(AttendanceSession::class, 'course_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'course_id');
    }
}
