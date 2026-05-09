<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Department;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            'Faculty of Science and Technology' => [
                'Department of Computer Science',
                'Department of Information Technology',
                'Department of Mathematics',
            ],
            'Faculty of Business' => [
                'Department of Accounting',
                'Department of Marketing',
                'Department of Business Administration',
            ],
            'Faculty of Engineering' => [
                'Department of Civil Engineering',
                'Department of Mechanical Engineering',
                'Department of Electrical Engineering',
            ],
        ];

        foreach ($faculties as $facultyName => $departments) {
            $faculty = Faculty::create(['faculty_name' => $facultyName]);
            foreach ($departments as $deptName) {
                Department::create([
                    'faculty_id' => $faculty->id,
                    'department_name' => $deptName,
                ]);
            }
        }
    }
}
