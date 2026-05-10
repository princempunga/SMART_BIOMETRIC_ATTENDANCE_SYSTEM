<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['full_name', 'reg_number', 'photo', 'faculty', 'department', 'fingerprint_id'];

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
