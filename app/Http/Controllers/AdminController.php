<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::count(),
            'lecturers' => User::where('role', 'lecturer')->count(),
            'active_sessions' => AttendanceSession::whereNull('session_end')->count(),
            'attendance_rate' => '85%', // Placeholder
        ];

        $recentAttendance = AttendanceLog::with(['student', 'session.course', 'session.classroom'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttendance'));
    }

    public function students()
    {
        $students = Student::latest()->get();
        return view('admin.students', compact('students'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reg_number' => 'required|string|unique:students',
            'faculty' => 'required|string',
            'department' => 'required|string',
        ]);

        Student::create($request->all());

        return redirect()->back()->with('success', 'Student registered successfully.');
    }

    public function lecturers()
    {
        $lecturers = User::where('role', 'lecturer')->latest()->get();
        return view('admin.lecturers', compact('lecturers'));
    }

    public function storeLecturer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'lecturer',
        ]);

        return redirect()->back()->with('success', 'Lecturer added successfully.');
    }

    public function courses()
    {
        $courses = Course::with('lecturer')->latest()->get();
        $lecturers = User::where('role', 'lecturer')->get();
        return view('admin.courses', compact('courses', 'lecturers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|unique:courses',
            'course_name' => 'required|string',
            'lecturer_id' => 'required|exists:users,id',
        ]);

        Course::create($request->all());

        return redirect()->back()->with('success', 'Course created successfully.');
    }

    public function classrooms()
    {
        $classrooms = Classroom::latest()->get();
        return view('admin.classrooms', compact('classrooms'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string',
            'device_id' => 'required|string|unique:classrooms',
        ]);

        Classroom::create($request->all());

        return redirect()->back()->with('success', 'Classroom added successfully.');
    }

    public function attendance()
    {
        $logs = AttendanceLog::with(['student', 'session.course', 'session.classroom'])
            ->latest()
            ->paginate(15);
        
        $courses = Course::all();
        
        return view('admin.attendance', compact('logs', 'courses'));
    }

    public function reports()
    {
        $courses = Course::all();
        // Placeholder for recent exports
        $exports = []; 
        
        return view('admin.reports', compact('courses', 'exports'));
    }

    public function generateReport(Request $request)
    {
        // Placeholder for report generation logic
        return redirect()->back()->with('success', 'Report generation started. You will be notified when it is ready.');
    }
}
