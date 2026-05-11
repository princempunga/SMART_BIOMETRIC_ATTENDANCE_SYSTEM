<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\AttendanceSession;
use App\Models\AttendanceLog;
use App\Models\Classroom;
use App\Models\Timetable;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturerId = Auth::id();
        $now = Carbon::now();
        $semesterStart = Carbon::parse('2026-01-20');
        $currentWeek = $now->diffInWeeks($semesterStart) + 1;

        // Auto-expire sessions
        AttendanceSession::whereIn('status', ['active', 'pending'])
            ->whereHas('timetable', function($q) use ($now) {
                $q->where('end_time', '<', $now->format('H:i'));
            })->update(['status' => 'expired']);

        $stats = [
            'courses' => Auth::user()->courseUnits()->count(),
            'active_sessions' => AttendanceSession::where('lecturer_id', $lecturerId)
                ->where('status', 'active')
                ->count(),
            'total_students' => 0,
            'avg_attendance' => '78%',
            'current_week' => $currentWeek
        ];

        $todayTimetable = Timetable::whereHas('course.lecturers', function($q) use ($lecturerId) {
                $q->where('users.id', $lecturerId);
            })
            ->where('day_of_week', $now->dayOfWeek)
            ->with(['course', 'classroom'])
            ->get();

        $activeSession = AttendanceSession::where('lecturer_id', $lecturerId)
            ->whereIn('status', ['active', 'pending'])
            ->with(['course', 'classroom', 'timetable'])
            ->first();

        $courses = Auth::user()->courseUnits;
        $classrooms = Classroom::all();

        $recentLogs = AttendanceLog::whereHas('session', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })->with(['student', 'session.course'])->latest()->take(5)->get();

        return view('lecturer.dashboard', compact('stats', 'courses', 'recentLogs', 'todayTimetable', 'activeSession', 'classrooms'));
    }

    public function startSession(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:course_units,id',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek;
        $currentTime = $now->format('H:i:s');

        // 1. Validate Classroom & Device Status
        $classroom = Classroom::with('device')->find($request->classroom_id);
        
        if ($classroom->status !== 'active') {
            return redirect()->back()->with('error', 'This classroom is currently marked as Inactive/Maintenance.');
        }

        if (!$classroom->device || $classroom->device->status !== 'active') {
            return redirect()->back()->with('error', 'No active biometric device found in this classroom. Please contact IT support.');
        }

        // 2. Prevent conflicting sessions in the same room
        $conflictingSession = AttendanceSession::where('classroom_id', $request->classroom_id)
            ->whereIn('status', ['active', 'pending'])
            ->first();

        if ($conflictingSession) {
            return redirect()->back()->with('error', 'There is already an active session in ' . $classroom->room_name . ' (' . $conflictingSession->course->course_name . ').');
        }

        // 3. Find timetable
        $timetable = Timetable::where('course_unit_id', $request->course_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$timetable) {
            return redirect()->back()->with('error', 'No timetable found for this course and classroom today.');
        }

        // 4. Validate start time (±15 mins)
        $startTime = Carbon::createFromFormat('H:i:s', $timetable->start_time);
        $diffInMinutes = $now->diffInMinutes($startTime, false);

        if (abs($diffInMinutes) > 15) {
            return redirect()->back()->with('error', 'Sessions can only be started within 15 minutes of their scheduled time. Scheduled: ' . $startTime->format('H:i'));
        }

        $semesterStart = Carbon::parse('2026-01-20');
        $currentWeek = $now->diffInWeeks($semesterStart) + 1;

        $otp = strtoupper(Str::random(6));

        $session = AttendanceSession::create([
            'course_unit_id' => $request->course_id,
            'lecturer_id' => Auth::id(),
            'classroom_id' => $request->classroom_id,
            'timetable_id' => $timetable->id,
            'session_start' => $now,
            'week_number' => $currentWeek,
            'otp' => $otp,
            'status' => 'pending'
        ]);

        return redirect()->route('lecturer.sessions.active', $session)->with('success', 'Session started! Please verify OTP to activate.');
    }

    public function activeSession(AttendanceSession $session)
    {
        if ($session->lecturer_id !== Auth::id()) {
            abort(403);
        }

        if ($session->isCompleted() || $session->isExpired()) {
            return redirect()->route('lecturer.dashboard')->with('info', 'Session is no longer active.');
        }

        return view('lecturer.sessions.active', compact('session'));
    }

    public function verifyOtp(Request $request, AttendanceSession $session)
    {
        // Simulation: Just activate it
        $session->update(['status' => 'active']);
        return redirect()->back()->with('success', 'OTP verified! Attendance is now being recorded.');
    }

    public function completeSession(AttendanceSession $session)
    {
        $session->update([
            'status' => 'completed',
            'session_end' => Carbon::now()
        ]);
        return redirect()->route('lecturer.dashboard')->with('success', 'Session completed successfully.');
    }

    public function getAttendanceCount(AttendanceSession $session)
    {
        return response()->json([
            'count' => $session->attendanceLogs()->count()
        ]);
    }

    public function getAttendanceLogs(AttendanceSession $session)
    {
        $logs = $session->attendanceLogs()
            ->with('student')
            ->latest()
            ->get()
            ->map(function($log) {
                return [
                    'student_name' => $log->student->full_name,
                    'reg_number' => $log->student->reg_number,
                    'clock_in' => $log->clock_in->format('H:i:s'),
                    'clock_out' => $log->clock_out ? $log->clock_out->format('H:i:s') : '—',
                    'duration' => $log->duration . ' mins',
                    'status' => $log->attendance_status
                ];
            });

        return response()->json($logs);
    }

    public function courses()
    {
        $courses = Auth::user()->courseUnits()->withCount(['sessions' => function($q) {
            $q->where('lecturer_id', Auth::id());
        }])->get();
        return view('lecturer.courses', compact('courses'));
    }

    public function sessions()
    {
        $sessions = AttendanceSession::where('lecturer_id', Auth::id())
            ->with(['course', 'classroom'])
            ->latest()
            ->paginate(10);
        
        $courses = Auth::user()->courseUnits;
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

        $courses = Auth::user()->courseUnits;

        return view('lecturer.attendance', compact('logs', 'courses'));
    }

    public function reports(Request $request)
    {
        $courses = Auth::user()->courseUnits;
        $selectedCourseId = $request->course_id ?? ($courses->first()->id ?? null);
        
        $students = Student::all(); // Assuming all students for simulation
        $reportData = [];

        if ($selectedCourseId) {
            $sessions = AttendanceSession::where('course_unit_id', $selectedCourseId)->get();
            
            foreach ($students as $student) {
                $studentWeeks = [];
                $totalDuration = 0;
                
                for ($w = 1; $w <= 16; $w++) {
                    // Find any log for this student in this week
                    $log = AttendanceLog::where('student_id', $student->id)
                        ->whereHas('session', function($q) use ($selectedCourseId, $w) {
                            $q->where('course_unit_id', $selectedCourseId)
                              ->where('week_number', $w);
                        })->first();
                    
                    $studentWeeks[$w] = $log ? true : false;
                    $totalDuration += $log ? ($log->duration ?? 0) : 0;
                }
                
                $reportData[] = [
                    'student' => $student,
                    'weeks' => $studentWeeks,
                    'total_duration' => $totalDuration,
                    'present_count' => count(array_filter($studentWeeks)),
                    'attendance_rate' => count($sessions) > 0 ? (count(array_filter($studentWeeks)) / count($sessions)) * 100 : 0
                ];
            }
        }

        return view('lecturer.reports', compact('courses', 'reportData', 'selectedCourseId'));
    }
}
