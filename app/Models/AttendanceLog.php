<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'student_id', 
        'session_id', 
        'clock_in', 
        'clock_out', 
        'duration', 
        'attendance_status',
        'attendance_mark',
        'week_number',
        'semester_id'
    ];

    public function calculateDurationAndMark($sessionDuration)
    {
        if ($this->clock_in && $this->clock_out) {
            $this->duration = $this->clock_in->diffInMinutes($this->clock_out);
            
            if ($sessionDuration > 0) {
                $percentage = ($this->duration / $sessionDuration) * 100;
                
                if ($percentage >= 80) {
                    $this->attendance_mark = 1.0;
                } elseif ($percentage >= 50) {
                    $this->attendance_mark = 0.7;
                } elseif ($percentage >= 20) {
                    $this->attendance_mark = 0.5;
                } else {
                    $this->attendance_mark = 0;
                    $this->attendance_status = 'absent';
                }
            }
        }
    }

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }
}
