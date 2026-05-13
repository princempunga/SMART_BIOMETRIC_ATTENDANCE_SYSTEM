<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\AttendanceLog;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $totalLogs = AttendanceLog::count();
        $totalSessions = \App\Models\AttendanceSession::count();
        $totalStudents = Student::count();
        
        $avgRate = 0;
        if ($totalSessions > 0 && $totalStudents > 0) {
            $avgRate = round(($totalLogs / ($totalSessions * $totalStudents)) * 100, 1);
        }

        $stats = [
            'students' => $totalStudents,
            'lecturers' => User::where('role', 'lecturer')->count(),
            'courses' => \App\Models\CourseUnit::count(),
            'classrooms' => Classroom::count(),
            'active_sessions' => \App\Models\AttendanceSession::where('status', 'active')->count(),
            'attendance_rate' => min($avgRate, 100) . '%',
        ];

        $recentAttendance = AttendanceLog::with(['student', 'session.course', 'session.classroom'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentAttendance'));
    }

    public function getDepartments($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        return response()->json($departments);
    }

    public function attendance(Request $request)
    {
        // 1. Data for Filter Dropdowns
        $allFaculties = Faculty::all();
        $allCourses = \App\Models\CourseUnit::all();

        // 2. Base query for Hierarchical Data
        $query = \App\Models\Faculty::with(['departments.courseUnits.sessions' => function($q) use ($request) {
            if ($request->filled('course_id')) {
                $q->where('course_unit_id', $request->course_id);
            }
            if ($request->filled('date')) {
                $q->whereDate('session_start', $request->date);
            }
        }, 'departments.courseUnits.sessions.attendanceLogs.student', 'departments.courseUnits.sessions.lecturer', 'departments.courseUnits.sessions.classroom']);

        if ($request->filled('faculty_id')) {
            $query->where('id', $request->faculty_id);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('departments', function($q) use ($request) {
                $q->where('id', $request->department_id);
            });
        }

        $faculties = $query->get();

        // 3. Stats for Summary Bar
        $today = \Carbon\Carbon::today();
        $totalSessionsToday = \App\Models\AttendanceSession::whereDate('session_start', $today)->count();
        $totalPresentToday = AttendanceLog::whereDate('clock_in', $today)->count();
        $totalStudentsCount = Student::count();
        
        $avgRateToday = 0;
        if ($totalSessionsToday > 0 && $totalStudentsCount > 0) {
            $avgRateToday = round(($totalPresentToday / ($totalSessionsToday * $totalStudentsCount)) * 100, 1);
        }

        $stats = [
            'total_sessions' => $totalSessionsToday,
            'total_present' => $totalPresentToday,
            'avg_rate' => min($avgRateToday, 100),
            'active_now' => \App\Models\AttendanceSession::where('status', 'active')->count(),
        ];

        return view('admin.attendance', compact('faculties', 'stats', 'allFaculties', 'allCourses'));
    }

    public function reports()
    {
        $courses = \App\Models\CourseUnit::all();
        $exports = collect([]); // Placeholder for reports history
        return view('admin.reports', compact('courses', 'exports'));
    }

    public function generateReport(Request $request)
    {
        // Placeholder for report generation logic
        return redirect()->back()->with('success', 'Report generation started.');
    }
}
