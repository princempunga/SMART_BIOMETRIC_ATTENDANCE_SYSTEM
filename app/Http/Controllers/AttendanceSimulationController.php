<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSession;
use App\Models\AttendanceLog;
use App\Models\Student;
use Carbon\Carbon;

class AttendanceSimulationController extends Controller
{
    public function index()
    {
        if (app()->environment('production')) {
            abort(403, 'Simulation not available in production.');
        }

        $activeSessions = AttendanceSession::where('status', 'active')
            ->with(['course', 'classroom'])
            ->get();
        
        $students = Student::all();

        return view('lecturer.test-environment', compact('activeSessions', 'students'));
    }

    public function simulateScan(Request $request)
    {
        if (app()->environment('production')) {
            abort(403, 'Simulation not available in production.');
        }

        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'student_id' => 'required|exists:students,id',
        ]);

        $session = AttendanceSession::findOrFail($request->session_id);
        
        if ($session->status !== 'active') {
            return response()->json(['error' => 'Session is not active.'], 400);
        }

        // Check if student already scanned
        $existing = AttendanceLog::where('session_id', $session->id)
            ->where('student_id', $request->student_id)
            ->first();
        
        if ($existing) {
            if (!$existing->clock_out) {
                // Perform clock out
                $clockOut = Carbon::now();
                $duration = $clockOut->diffInMinutes($existing->clock_in);
                
                $existing->update([
                    'clock_out' => $clockOut,
                    'duration' => $duration
                ]);
                return response()->json(['success' => 'Student clocked out!']);
            }
            return response()->json(['error' => 'Student already completed their attendance.'], 400);
        }

        AttendanceLog::create([
            'session_id' => $session->id,
            'student_id' => $request->student_id,
            'clock_in' => Carbon::now(),
            'attendance_status' => 'present'
        ]);

        return response()->json(['success' => 'Attendance recorded (Clocked-in)!']);
    }
}
