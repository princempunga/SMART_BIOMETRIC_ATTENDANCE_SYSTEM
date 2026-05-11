<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
    public function clockIn(Request $request)
    {
        $request->validate([
            'fingerprint_id' => 'required|integer',
            'session_id' => 'required|integer',
        ]);

        $student = Student::where('fingerprint_id', $request->fingerprint_id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $session = AttendanceSession::find($request->session_id);
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        // Check if already clocked in
        $existingLog = AttendanceLog::where('student_id', $student->id)
            ->where('session_id', $session->id)
            ->first();

        if ($existingLog) {
            return response()->json(['message' => 'Already clocked in'], 400);
        }

        $log = AttendanceLog::create([
            'student_id' => $student->id,
            'session_id' => $session->id,
            'clock_in' => now(),
            'attendance_status' => 'present',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Clock-in successful',
            'student' => $student->full_name,
            'time' => $log->clock_in->format('H:i:s')
        ]);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'fingerprint_id' => 'required|integer',
            'session_id' => 'required|integer',
        ]);

        $student = Student::where('fingerprint_id', $request->fingerprint_id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $log = AttendanceLog::where('student_id', $student->id)
            ->where('session_id', $request->session_id)
            ->whereNull('clock_out')
            ->first();

        if (!$log) {
            return response()->json(['message' => 'No active clock-in found'], 404);
        }

        $clockOutTime = now();
        $duration = Carbon::parse($log->clock_in)->diffInMinutes($clockOutTime);

        $log->update([
            'clock_out' => $clockOutTime,
            'duration' => $duration
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Clock-out successful',
            'student' => $student->full_name,
            'duration' => $duration . ' mins'
        ]);
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'fingerprint_id' => 'required|integer',
            'reg_number' => 'required|string',
        ]);

        $student = Student::where('reg_number', $request->reg_number)->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Check if fingerprint ID is already taken by another student
        $existing = Student::where('fingerprint_id', $request->fingerprint_id)
            ->where('id', '!=', $student->id)
            ->first();
            
        if ($existing) {
            return response()->json(['message' => 'Fingerprint ID already assigned to another student'], 400);
        }

        $student->update([
            'fingerprint_id' => $request->fingerprint_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Enrolled: ' . $student->full_name
        ]);
    }
    public function sync(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'logs' => 'required|array',
            'logs.*.fingerprint_id' => 'required|integer',
            'logs.*.timestamp' => 'required|date',
            'logs.*.type' => 'required|in:clock_in,clock_out',
        ]);

        $session = AttendanceSession::find($request->session_id);
        if (!$session) return response()->json(['message' => 'Session not found'], 404);

        $syncedCount = 0;
        foreach ($request->logs as $logData) {
            $student = Student::where('fingerprint_id', $logData['fingerprint_id'])->first();
            if (!$student) continue;

            // Check for existing log to prevent duplicates during sync
            $exists = AttendanceLog::where('student_id', $student->id)
                ->where('session_id', $session->id)
                ->where('clock_in', $logData['timestamp'])
                ->exists();

            if (!$exists) {
                AttendanceLog::create([
                    'student_id' => $student->id,
                    'session_id' => $session->id,
                    'clock_in' => $logData['timestamp'],
                    'attendance_status' => 'present',
                    'is_offline_sync' => true // Metadata to track sync
                ]);
                $syncedCount++;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Synchronized $syncedCount logs",
            'total_in_session' => $session->attendanceLogs()->count()
        ]);
    }

    public function getStudentCache(Request $request)
    {
        $request->validate(['device_code' => 'required|string']);
        
        // Return students relevant to this device's classroom (or just all for simplicity in simulation)
        $students = Student::select('id', 'full_name', 'reg_number', 'fingerprint_id')->get();
        
        return response()->json([
            'status' => 'success',
            'count' => $students->count(),
            'students' => $students
        ]);
    }
}
