<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\CourseUnit;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Support\Facades\Storage;

class DeanController extends Controller
{
    private function getFacultyId()
    {
        return Auth::user()->faculty_id;
    }

    public function index()
    {
        $facultyId = $this->getFacultyId();
        $faculty   = Faculty::find($facultyId);

        $totalStudents    = Student::where('faculty_id', $facultyId)->count();
        $totalLecturers   = User::where('role', 'lecturer')->where('faculty_id', $facultyId)->count();
        $totalDepartments = Department::where('faculty_id', $facultyId)->count();
        $totalCourses     = CourseUnit::where('faculty_id', $facultyId)->count();

        // Attendance rate for this faculty
        $sessionsInFaculty = AttendanceSession::whereHas('course', function($q) use ($facultyId) {
            $q->where('faculty_id', $facultyId);
        })->count();

        $logsInFaculty = AttendanceLog::whereHas('session.course', function($q) use ($facultyId) {
            $q->where('faculty_id', $facultyId);
        })->count();

        $attendanceRate = ($sessionsInFaculty > 0 && $totalStudents > 0)
            ? min(round(($logsInFaculty / ($sessionsInFaculty * $totalStudents)) * 100, 1), 100)
            : 0;

        $stats = [
            'students'      => $totalStudents,
            'lecturers'     => $totalLecturers,
            'departments'   => $totalDepartments,
            'courses'       => $totalCourses,
            'attendance_rate' => $attendanceRate . '%',
        ];

        $recentAttendance = AttendanceLog::with(['student', 'session.course', 'session.classroom'])
            ->whereHas('student', fn($q) => $q->where('faculty_id', $facultyId))
            ->latest()->take(5)->get();

        $departments = Department::where('faculty_id', $facultyId)->withCount('students')->get();

        return view('dean.dashboard', compact('stats', 'recentAttendance', 'departments', 'faculty'));
    }

    public function students(Request $request)
    {
        $facultyId = $this->getFacultyId();
        $departments = Department::where('faculty_id', $facultyId)->get();

        $query = Student::where('faculty_id', $facultyId)->with('department');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('reg_number', 'like', '%' . $request->search . '%')
                  ->orWhere('fingerprint_id', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate(20);

        return view('dean.students', compact('students', 'departments'));
    }

    public function storeStudent(Request $request)
    {
        $facultyId = $this->getFacultyId();
        
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'reg_number' => 'required|string|unique:students,reg_number',
            'department_id' => 'required|exists:departments,id',
            'fingerprint_id' => 'required|integer|unique:students,fingerprint_id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $data['faculty_id'] = $facultyId;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('student_photos', 'public');
        }

        Student::create($data);

        return redirect()->back()->with('success', 'Student registered successfully.');
    }

    public function updateStudent(Request $request, Student $student)
    {
        $facultyId = $this->getFacultyId();
        
        // Security check
        if ($student->faculty_id != $facultyId) {
            abort(403);
        }

        try {
            $data = $request->validate([
                'full_name' => 'required|string|max:255',
                'reg_number' => 'required|string|unique:students,reg_number,' . $student->id,
                'department_id' => 'required|exists:departments,id',
                'fingerprint_id' => 'required|integer|unique:students,fingerprint_id,' . $student->id,
                'photo' => 'nullable|image|mimes:jpeg,png,jpg',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('edit_student_id', $student->id);
            throw $e;
        }

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('student_photos', 'public');
        }

        $student->update($data);

        return redirect()->back()->with('success', 'Student profile updated successfully.');
    }

    public function destroyStudent(Student $student)
    {
        $facultyId = $this->getFacultyId();
        
        // Security check
        if ($student->faculty_id != $facultyId) {
            abort(403);
        }

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function lecturers()
    {
        $facultyId = $this->getFacultyId();
        $lecturers = User::where('role', 'lecturer')
            ->where('faculty_id', $facultyId)
            ->with(['department', 'courseUnits'])
            ->get();

        return view('dean.lecturers', compact('lecturers'));
    }

    public function attendance(Request $request)
    {
        $facultyId = $this->getFacultyId();

        $query = AttendanceLog::with(['student', 'session.course', 'session.classroom'])
            ->whereHas('student', fn($q) => $q->where('faculty_id', $facultyId));

        if ($request->filled('date')) {
            $query->whereDate('clock_in', $request->date);
        }

        $logs = $query->latest()->paginate(20);

        return view('dean.attendance', compact('logs'));
    }

    public function reports(Request $request)
    {
        $facultyId = $this->getFacultyId();

        $courses = CourseUnit::where('faculty_id', $facultyId)->get();
        $selectedCourseId = $request->course_id ?? ($courses->first()->id ?? null);

        $reportData       = [];
        $weeksWithSessions = [];

        if ($selectedCourseId) {
            $course = CourseUnit::find($selectedCourseId);

            // Only students in this faculty's department
            $students = Student::where('department_id', $course->department_id)
                ->orWhereHas('attendanceLogs.session', fn($q) => $q->where('course_unit_id', $selectedCourseId))
                ->where('faculty_id', $facultyId)
                ->get();

            $weeksWithSessions = AttendanceSession::where('course_unit_id', $selectedCourseId)
                ->distinct()->pluck('week_number')->toArray();

            $totalRecordedWeeks = 16;

            foreach ($students as $student) {
                $studentWeeks     = [];
                $totalDuration    = 0;
                $presentWeeksCount = 0;

                for ($w = 1; $w <= 16; $w++) {
                    $log = AttendanceLog::where('student_id', $student->id)
                        ->whereHas('session', fn($q) => $q->where('course_unit_id', $selectedCourseId)->where('week_number', $w))
                        ->first();

                    $isPresent = (bool) $log;
                    $studentWeeks[$w] = $isPresent;
                    $totalDuration   += $log ? ($log->duration ?? 0) : 0;
                    if ($isPresent) $presentWeeksCount++;
                }

                $scoreOutOf5 = ($presentWeeksCount / $totalRecordedWeeks) * 5;

                $reportData[] = [
                    'student'         => $student,
                    'weeks'           => $studentWeeks,
                    'total_duration'  => $totalDuration,
                    'present_count'   => $presentWeeksCount,
                    'score_out_of_5'  => round($scoreOutOf5, 2),
                    'attendance_rate' => ($presentWeeksCount / $totalRecordedWeeks) * 100,
                ];
            }
        }

        return view('dean.reports', compact('courses', 'reportData', 'selectedCourseId', 'weeksWithSessions'));
    }
}
