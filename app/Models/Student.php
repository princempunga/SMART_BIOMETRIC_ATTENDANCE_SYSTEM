<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['full_name', 'reg_number', 'photo', 'faculty_id', 'department_id', 'fingerprint_id'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
