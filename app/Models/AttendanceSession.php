<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = ['course_id', 'lecturer_id', 'classroom_id', 'timetable_id', 'session_start', 'session_end', 'week_number', 'otp', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'session_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isExpired()
    {
        return $this->status === 'expired';
    }
}
