<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = ['course_unit_id', 'lecturer_id', 'classroom_id', 'timetable_id', 'session_start', 'session_end', 'week_number', 'otp', 'status'];

    protected $casts = [
        'session_start' => 'datetime',
        'session_end' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(CourseUnit::class, 'course_unit_id');
    }

    /**
     * Alias for course relationship to prevent legacy/typo errors
     */
    public function courseUnit()
    {
        return $this->course();
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

    public function getDurationAttribute()
    {
        if ($this->session_start && $this->session_end) {
            return \Carbon\Carbon::parse($this->session_start)->diffInMinutes(\Carbon\Carbon::parse($this->session_end));
        }
        return 0;
    }
}
