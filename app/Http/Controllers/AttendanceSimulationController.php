<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSession;
use App\Models\AttendanceLog;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseUnit;
use App\Models\Faculty;
use App\Models\ReportExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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

        $existing = AttendanceLog::where('session_id', $session->id)
            ->where('student_id', $request->student_id)
            ->first();
        
        if ($existing) {
            if (!$existing->clock_out) {
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
            'week_number' => $session->week_number,
            'semester_id' => 'SEM1-2026', // Dynamic based on app settings usually
            'attendance_status' => 'present'
        ]);

        return response()->json(['success' => 'Attendance recorded (Clocked-in)!']);
    }

    public function seedDemoData()
    {
        // 1. Create Admin if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@smartattend.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        // 2. Create Faculty & Department if empty
        $faculty = Faculty::firstOrCreate(['faculty_name' => 'Science and Technology']);
        $dept = \App\Models\Department::firstOrCreate([
            'department_name' => 'Computer Science',
            'faculty_id' => $faculty->id
        ]);

        // 3. Create Demo Lecturers
        $lecturer = User::firstOrCreate(
            ['email' => 'lecturer@university.edu'],
            [
                'name' => 'John Lecturer',
                'password' => Hash::make('password'),
                'role' => 'lecturer',
                'faculty_id' => $faculty->id,
                'department_id' => $dept->id
            ]
        );

        // 4. Create Students
        for ($i = 1; $i <= 10; $i++) {
            Student::firstOrCreate(
                ['reg_number' => "CS/2024/00$i"],
                [
                    'full_name' => "Student $i",
                    'faculty_id' => $faculty->id,
                    'department_id' => $dept->id,
                    'fingerprint_id' => $i
                ]
            );
        }

        // 5. Create Course Units (NEW ARCHITECTURE)
        $courseUnits = [];
        $faculties = Faculty::all();
        foreach($faculties as $fac) {
            $prefix = strtoupper(substr($fac->faculty_name, 0, 3));
            for($i = 1; $i <= 3; $i++) {
                $courseUnits[] = CourseUnit::firstOrCreate(
                    ['course_code' => $prefix . rand(100, 999)],
                    [
                        'course_name' => 'Advanced ' . $fac->faculty_name . ' Module ' . $i,
                        'faculty_id' => $fac->id,
                    ]
                );
            }
        }

        // 6. Assign some units to the demo lecturer
        $lecturer->courseUnits()->sync(array_slice(array_column($courseUnits, 'id'), 0, 2));

        return redirect()->route('admin.reports')->with('success', 'SmartAttend Demo Data Seeded! You can now test the Course Units module.');
    }
}
