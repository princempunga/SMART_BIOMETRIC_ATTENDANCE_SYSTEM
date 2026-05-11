<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\CourseUnit;
use App\Models\AttendanceSession;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class HtmlTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Faculty, Dept, Lecturer
        $faculty = Faculty::firstOrCreate(['faculty_name' => 'Faculty of Science and Technology']);
        $dept = Department::firstOrCreate([
            'department_name' => 'Department of Computer Science',
            'faculty_id' => $faculty->id
        ]);

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

        $classroom = Classroom::firstOrCreate(
            ['room_name' => 'Lab 1'],
            ['building_name' => 'Tech Wing', 'status' => 'active']
        );

        // 2. Create a Course Unit for HTML
        $course = CourseUnit::firstOrCreate(
            ['course_code' => 'HTML101'],
            [
                'course_name' => 'HTML & Web Development',
                'faculty_id' => $faculty->id,
            ]
        );
        $lecturer->courseUnits()->syncWithoutDetaching([$course->id]);

        // 3. Create 10 Students for Computer Science
        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $students[] = Student::firstOrCreate(
                ['reg_number' => "CS/HTML/2026/00$i"],
                [
                    'full_name' => "CS Student $i",
                    'faculty_id' => $faculty->id,
                    'department_id' => $dept->id,
                    'fingerprint_id' => 200 + $i
                ]
            );
        }

        // 4. Create 16 Weeks of Sessions
        for ($w = 1; $w <= 16; $w++) {
            $session = AttendanceSession::create([
                'course_unit_id' => $course->id,
                'lecturer_id' => $lecturer->id,
                'classroom_id' => $classroom->id,
                'session_start' => Carbon::now()->subWeeks(17 - $w)->setHour(10)->setMinute(0),
                'session_end' => Carbon::now()->subWeeks(17 - $w)->setHour(12)->setMinute(0),
                'week_number' => $w,
                'status' => 'completed'
            ]);

            // 5. Assign attendance
            // 7 students present all 16 weeks (Student 1 to 7)
            for ($s = 0; $s < 7; $s++) {
                AttendanceLog::create([
                    'session_id' => $session->id,
                    'student_id' => $students[$s]->id,
                    'clock_in' => $session->session_start->copy()->addMinutes(rand(1, 15)),
                    'clock_out' => $session->session_end->copy()->subMinutes(rand(1, 15)),
                    'duration' => 105,
                    'attendance_status' => 'present'
                ]);
            }

            // 3 students (Student 8 to 10) absent for most weeks, present for 2 weeks
            // Let's say they are present only in week 1 and 2
            if ($w <= 2) {
                for ($s = 7; $s < 10; $s++) {
                    AttendanceLog::create([
                        'session_id' => $session->id,
                        'student_id' => $students[$s]->id,
                        'clock_in' => $session->session_start->copy()->addMinutes(rand(1, 15)),
                        'clock_out' => $session->session_end->copy()->subMinutes(rand(1, 15)),
                        'duration' => 105,
                        'attendance_status' => 'present'
                    ]);
                }
            }
        }
    }
}
