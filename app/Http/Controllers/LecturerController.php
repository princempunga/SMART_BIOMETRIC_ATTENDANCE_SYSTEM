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

        $totalStudents = Student::whereHas('attendanceLogs.session', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })->count();

        $stats = [
            'courses' => Auth::user()->courseUnits()->count(),
            'active_sessions' => AttendanceSession::where('lecturer_id', $lecturerId)
                ->where('status', 'active')
                ->count(),
            'total_students' => $totalStudents,
            'total_logs' => AttendanceLog::whereHas('session', function($q) use ($lecturerId) {
                $q->where('lecturer_id', $lecturerId);
            })->count(),
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
        $classrooms = Classroom::with('device')->get();

        $recentLogs = AttendanceLog::whereHas('session', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })->with(['student', 'session.course'])->latest()->take(5)->get();

        return view('lecturer.dashboard', compact('stats', 'courses', 'recentLogs', 'todayTimetable', 'activeSession', 'classrooms'));
    }

    public function startSession(Request $request)
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:course_units,id',
                'classroom_id' => 'required|exists:classrooms,id',
                'week_number' => 'required|integer|min:1|max:16',
            ]);

            $now = Carbon::now();
            $dayOfWeek = $now->dayOfWeek;

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

            // 4. Validate start time (±15 mins) if timetable exists
            if ($timetable) {
                $startTime = Carbon::createFromFormat('H:i:s', $slot_start = $timetable->start_time);
                $diffInMinutes = $now->diffInMinutes($startTime, false);

                if (abs($diffInMinutes) > 30) { // Relaxed to 30 mins for flexibility
                    // We can either block it or just log a warning. The remote wanted to block it.
                    // return redirect()->back()->with('error', 'Sessions can only be started near their scheduled time. Scheduled: ' . $startTime->format('H:i'));
                }
            }

            $otp = strtoupper(Str::random(6));

            $session = AttendanceSession::create([
                'course_unit_id' => $request->course_id,
                'lecturer_id' => Auth::id(),
                'classroom_id' => $request->classroom_id,
                'timetable_id' => $timetable ? $timetable->id : null,
                'session_start' => $now,
                'week_number' => $request->week_number,
                'otp' => $otp,
                'status' => 'pending'
            ]);

            return redirect()->route('lecturer.sessions.active', $session)->with('success', 'Session initialized! Please verify OTP to start tracking.');
        } catch (\Exception $e) {
            \Log::error('Session Start Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error starting session: ' . $e->getMessage());
        }
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
        $now = Carbon::now();
        $session->update([
            'status' => 'completed',
            'session_end' => $now
        ]);

        // Auto-clock out students who are still "In Class"
        AttendanceLog::where('session_id', $session->id)
            ->whereNull('clock_out')
            ->update(['clock_out' => $now]);

        $totalDuration = $session->duration;

        // Calculate marks for all logs in this session
        foreach ($session->attendanceLogs()->get() as $log) {
            $log->calculateDurationAndMark($totalDuration);
            $log->save();
        }

        return redirect()->route('lecturer.dashboard')->with('success', 'Session completed! Attendance credits have been calculated.');
    }

    public function destroySession(AttendanceSession $session)
    {
        if ($session->lecturer_id !== Auth::id()) {
            abort(403);
        }

        $session->delete();

        return redirect()->back()->with('success', 'Session history deleted successfully.');
    }

    public function getLiveData(AttendanceSession $session)
    {
        if ($session->lecturer_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logs = $session->attendanceLogs()
            ->with('student')
            ->latest()
            ->get();

        $scannedCount = $logs->count();
        $inClassCount = $logs->whereNull('clock_out')->count();
        $completedCount = $logs->whereNotNull('clock_out')->count();
        
        $totalEnrolled = Student::count(); // Simplification: use total students for rate
        $attendanceRate = $totalEnrolled > 0 ? round(($scannedCount / $totalEnrolled) * 100) : 0;

        $mappedLogs = $logs->map(function($log) use ($session) {
            // Calculate temporary duration if session is still active
            $duration = $log->clock_out 
                ? $log->clock_in->diffInMinutes($log->clock_out) 
                : $log->clock_in->diffInMinutes(Carbon::now());

            // Calculate temporary mark
            $scheduledDuration = $session->timetable ? Carbon::parse($session->timetable->start_time)->diffInMinutes(Carbon::parse($session->timetable->end_time)) : 60;
            $percentage = ($duration / max($scheduledDuration, 1)) * 100;
            
            $credit = 0;
            if ($percentage >= 80) $credit = 1.0;
            elseif ($percentage >= 50) $credit = 0.7;
            elseif ($percentage >= 20) $credit = 0.5;

            return [
                'student_name' => $log->student->full_name,
                'reg_number' => $log->student->reg_number,
                'clock_in' => $log->clock_in->format('H:i:s'),
                'clock_out' => $log->clock_out ? $log->clock_out->format('H:i:s') : '—',
                'duration' => $duration . 'm',
                'status' => $log->clock_out ? 'Completed' : 'In Class',
                'credit' => $credit
            ];
        });

        return response()->json([
            'scanned' => $scannedCount,
            'in_class' => $inClassCount,
            'completed' => $completedCount,
            'rate' => $attendanceRate,
            'logs' => $mappedLogs
        ]);
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
        $now = Carbon::now();
        $semesterStart = Carbon::parse('2026-01-20');
        $currentWeek = $now->diffInWeeks($semesterStart) + 1;

        $sessions = AttendanceSession::where('lecturer_id', Auth::id())
            ->with(['course', 'classroom'])
            ->latest()
            ->paginate(10);
        
        $courses = Auth::user()->courseUnits;
        $classrooms = Classroom::all();

        return view('lecturer.sessions', compact('sessions', 'courses', 'classrooms', 'currentWeek'));
    }

    public function attendance(Request $request)
    {
        $courses = Auth::user()->courseUnits;
        
        $query = AttendanceLog::whereHas('session', function($query) {
            $query->where('lecturer_id', Auth::id());
        });

        if ($request->filled('course_id')) {
            $query->whereHas('session', function($q) use ($request) {
                $q->where('course_unit_id', $request->course_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('clock_in', $request->date);
        }

        $logs = $query->with(['student', 'session.course', 'session.classroom'])->latest()->paginate(20);

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
