<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
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
        $attendanceRate = $totalSessions > 0
            ? round(($totalLogs / max($totalSessions, 1)) * 10, 1)
            : 0;
        $attendanceRate = min($attendanceRate, 100);

        $stats = [
            'students' => Student::count(),
            'lecturers' => User::where('role', 'lecturer')->count(),
            'courses' => Course::count(),
            'classrooms' => Classroom::count(),
            'active_sessions' => \App\Models\AttendanceSession::whereNull('session_end')->count(),
            'attendance_rate' => $attendanceRate . '%',
        ];

        $recentAttendance = AttendanceLog::with(['student', 'session.course', 'session.classroom'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentAttendance'));
    }

    public function lecturers()
    {
        $lecturers = User::where('role', 'lecturer')->with(['faculty', 'department', 'courses'])->get();
        $faculties = Faculty::all();
        return view('admin.lecturers', compact('lecturers', 'faculties'));
    }

    public function getDepartments($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        return response()->json($departments);
    }

    public function storeLecturer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'lecturer',
            'faculty_id' => $request->faculty_id,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'profile_photo' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Lecturer registered successfully.');
    }

    public function updateLecturer(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'email', 'faculty_id', 'department_id', 'phone');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Lecturer updated successfully.');
    }

    public function destroyLecturer(User $user)
    {
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        $user->delete();
        return redirect()->back()->with('success', 'Lecturer deleted successfully.');
    }

    // --- Student Management ---
    public function students()
    {
        $students = Student::all();
        return view('admin.students', compact('students'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'reg_number' => 'required|string|unique:students',
            'faculty' => 'required|string',
            'department' => 'required|string',
        ]);

        Student::create($request->all());
        return redirect()->back()->with('success', 'Student registered successfully.');
    }

    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string',
            'reg_number' => 'required|string|unique:students,reg_number,'.$student->id,
            'faculty' => 'required|string',
            'department' => 'required|string',
        ]);

        $student->update($request->all());
        return redirect()->back()->with('success', 'Student updated successfully.');
    }

    public function destroyStudent(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    // --- Course Management ---
    public function courses()
    {
        $courses = Course::with('lecturer')->get();
        return view('admin.courses', compact('courses'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string',
            'course_code' => 'required|string|unique:courses',
            'lecturer_id' => 'required|exists:users,id',
        ]);

        Course::create($request->all());
        return redirect()->back()->with('success', 'Course added successfully.');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'course_name' => 'required|string',
            'course_code' => 'required|string|unique:courses,course_code,'.$course->id,
            'lecturer_id' => 'required|exists:users,id',
        ]);

        $course->update($request->all());
        return redirect()->back()->with('success', 'Course updated successfully.');
    }

    public function destroyCourse(Course $course)
    {
        $course->delete();
        return redirect()->back()->with('success', 'Course deleted successfully.');
    }

    // --- Classroom Management ---
    public function classrooms()
    {
        $classrooms = Classroom::all();
        return view('admin.classrooms', compact('classrooms'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string',
            'device_id' => 'required|string|unique:classrooms',
        ]);

        Classroom::create($request->all());
        return redirect()->back()->with('success', 'Classroom registered successfully.');
    }

    public function updateClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'room_name' => 'required|string',
            'device_id' => 'required|string|unique:classrooms,device_id,'.$classroom->id,
        ]);

        $classroom->update($request->all());
        return redirect()->back()->with('success', 'Classroom updated successfully.');
    }

    public function destroyClassroom(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->back()->with('success', 'Classroom deleted successfully.');
    }

    public function attendance()
    {
        $logs = AttendanceLog::with(['student', 'session.course', 'session.classroom'])->latest()->paginate(15);
        $courses = Course::all();
        return view('admin.attendance', compact('logs', 'courses'));
    }

    public function reports()
    {
        $courses = Course::all();
        $exports = collect([]); // Placeholder for reports history
        return view('admin.reports', compact('courses', 'exports'));
    }

    public function generateReport(Request $request)
    {
        // Placeholder for report generation logic
        return redirect()->back()->with('success', 'Report generation started.');
    }
}
