<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\CourseUnit;
use App\Models\AttendanceSession;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class AttendanceLogsController extends Controller
{
    public function index(Request $request)
    {
        $today = today();
        $filterDate = $request->date ? Carbon::parse($request->date) : $today;

        // Hierarchical Data Query
        $facultiesQuery = Faculty::with([
            'departments' => function($q) use ($request) {
                if ($request->filled('department_id')) {
                    $q->where('id', $request->department_id);
                }
            },
            'departments.courseUnits.sessions' => function($q) use ($request, $filterDate) {
                $q->whereDate('session_start', $filterDate);
                
                if ($request->filled('course_id')) {
                    $q->where('course_unit_id', $request->course_id);
                }
                
                if ($request->filled('status')) {
                    $q->where('status', $request->status);
                }
            },
            'departments.courseUnits.sessions.attendanceLogs.student',
            'departments.courseUnits.sessions.lecturer',
            'departments.courseUnits.sessions.classroom'
        ]);

        if ($request->filled('faculty_id')) {
            $facultiesQuery->where('id', $request->faculty_id);
        }

        $faculties = $facultiesQuery->get();

        // Summary Stats
        $totalSessionsToday = AttendanceSession::whereDate('session_start', $today)->count();
        $totalStudentsPresent = AttendanceLog::whereHas('session', function($q) use ($today) {
            $q->whereDate('session_start', $today);
        })->count();
        
        $activeSessionsNow = AttendanceSession::where('status', 'active')->count();
        
        // Calculate Global Average Rate for today
        $sessionsToday = AttendanceSession::whereDate('session_start', $today)->get();
        $totalRate = 0;
        foreach($sessionsToday as $session) {
            $presentCount = $session->attendanceLogs()->count();
            // Assuming we need a base to calculate rate, let's say 45 students per session for simplicity if not defined
            $sessionRate = $presentCount > 0 ? min(($presentCount / 45) * 100, 100) : 0;
            $totalRate += $sessionRate;
        }
        $avgRate = $sessionsToday->count() > 0 ? round($totalRate / $sessionsToday->count(), 1) : 0;

        // Data for Filters
        $allFaculties = Faculty::all();
        $allCourses = CourseUnit::all();

        return view('admin.attendance', [
            'faculties' => $faculties,
            'allFaculties' => $allFaculties,
            'allCourses' => $allCourses,
            'stats' => [
                'total_sessions' => $totalSessionsToday,
                'total_present' => $totalStudentsPresent,
                'active_now' => $activeSessionsNow,
                'avg_rate' => $avgRate
            ]
        ]);
    }
}
