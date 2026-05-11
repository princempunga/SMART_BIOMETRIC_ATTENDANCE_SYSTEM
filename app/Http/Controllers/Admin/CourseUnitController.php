<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseUnit;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;

class CourseUnitController extends Controller
{
    public function index()
    {
        $courseUnits = CourseUnit::with(['faculty', 'department'])->get();
        $faculties = Faculty::all();
        $departments = Department::all();
        return view('admin.course-units.index', compact('courseUnits', 'faculties', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|unique:course_units,course_code|max:50',
            'course_name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        CourseUnit::create($request->all());

        return redirect()->route('admin.course-units.index')->with('success', 'Course Unit created successfully.');
    }

    public function update(Request $request, CourseUnit $course_unit)
    {
        $request->validate([
            'course_code' => 'required|string|max:50|unique:course_units,course_code,' . $course_unit->id,
            'course_name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $course_unit->update($request->all());

        return redirect()->route('admin.course-units.index')->with('success', 'Course Unit updated successfully.');
    }

    public function destroy(CourseUnit $course_unit)
    {
        $course_unit->delete();
        return redirect()->route('admin.course-units.index')->with('success', 'Course Unit deleted successfully.');
    }

    public function getByFaculty(Faculty $faculty)
    {
        $courseUnits = CourseUnit::where('faculty_id', $faculty->id)->get();
        return response()->json($courseUnits);
    }
}
