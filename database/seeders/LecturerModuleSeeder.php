<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\Timetable;
use App\Models\Student;
use Carbon\Carbon;

class LecturerModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure a lecturer exists
        $lecturer = User::where('role', 'lecturer')->first();
        if (!$lecturer) {
            $lecturer = User::create([
                'name' => 'John Lecturer',
                'email' => 'lecturer@university.edu',
                'password' => bcrypt('password'),
                'role' => 'lecturer'
            ]);
        }

        // 2. Create a classroom
        $classroom = Classroom::updateOrCreate(
            ['room_name' => 'Lab 01'],
            ['device_id' => 'ESP32_L1']
        );
        Classroom::updateOrCreate(
            ['room_name' => 'Lecture Hall 4'],
            ['device_id' => 'ESP32_H4']
        );

        // 3. Create a course and assign to lecturer
        $course = Course::updateOrCreate(
            ['course_code' => 'CSC302'],
            ['course_name' => 'Advanced Database Systems', 'lecturer_id' => $lecturer->id]
        );

        Course::updateOrCreate(
            ['course_code' => 'CSC305'],
            ['course_name' => 'Mobile App Development', 'lecturer_id' => $lecturer->id]
        );

        // 4. Create Timetable entries for TODAY
        $now = Carbon::now();
        
        // Slot 1: Starts now
        Timetable::updateOrCreate(
            [
                'course_id' => $course->id,
                'classroom_id' => $classroom->id,
                'day_of_week' => $now->dayOfWeek,
            ],
            [
                'start_time' => $now->copy()->subMinutes(5)->format('H:i:s'),
                'end_time' => $now->copy()->addHours(2)->format('H:i:s'),
            ]
        );

        // 5. Create test students
        $students = [
            ['full_name' => 'Alice Johnson', 'reg_number' => 'S21/CS/001', 'fingerprint_id' => 1, 'faculty' => 'Computing', 'department' => 'Computer Science'],
            ['full_name' => 'Bob Smith', 'reg_number' => 'S21/CS/002', 'fingerprint_id' => 2, 'faculty' => 'Computing', 'department' => 'Computer Science'],
            ['full_name' => 'Charlie Brown', 'reg_number' => 'S21/CS/003', 'fingerprint_id' => 3, 'faculty' => 'Computing', 'department' => 'Computer Science'],
            ['full_name' => 'Diana Prince', 'reg_number' => 'S21/CS/004', 'fingerprint_id' => 4, 'faculty' => 'Computing', 'department' => 'Computer Science'],
            ['full_name' => 'Ethan Hunt', 'reg_number' => 'S21/CS/005', 'fingerprint_id' => 5, 'faculty' => 'Computing', 'department' => 'Computer Science'],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(['reg_number' => $s['reg_number']], $s);
        }
    }
}
