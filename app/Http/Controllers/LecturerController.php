<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\AttendanceSession;
use App\Models\AttendanceLog;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturerId = Auth::id();

        $stats = [
            'courses' => Course::where('lecturer_id', $lecturerId)->count(),
            'active_sessions' => AttendanceSession::where('lecturer_id', $lecturerId)
                ->whereNull('session_end')
                ->count(),
            'total_students' => 0,
            'avg_attendance' => '78%',
        ];

        $courses = Course::where('lecturer_id', $lecturerId)->get();

        $recentLogs = AttendanceLog::whereHas('session', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })->with(['student', 'session.course'])->latest()->take(5)->get();

        return view('lecturer.dashboard', compact('stats', 'courses', 'recentLogs'));
    }

    public function courses()
    {
        $courses = Course::where('lecturer_id', Auth::id())->withCount('sessions')->get();
        return view('lecturer.courses', compact('courses'));
    }

    public function sessions()
    {
        $sessions = AttendanceSession::where('lecturer_id', Auth::id())
            ->with(['course', 'classroom'])
            ->latest()
            ->paginate(10);
        
        $courses = Course::where('lecturer_id', Auth::id())->get();
        $classrooms = Classroom::all();

        return view('lecturer.sessions', compact('sessions', 'courses', 'classrooms'));
    }

    public function attendance()
    {
        $logs = AttendanceLog::whereHas('session', function($q) {
            $q->where('lecturer_id', Auth::id());
        })
        ->with(['student', 'session.course', 'session.classroom'])
        ->latest()
        ->paginate(15);

        $courses = Course::where('lecturer_id', Auth::id())->get();

        return view('lecturer.attendance', compact('logs', 'courses'));
    }
}
