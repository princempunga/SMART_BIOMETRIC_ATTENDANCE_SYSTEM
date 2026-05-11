<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['faculty', 'department'])->latest()->get();
        $faculties = Faculty::all();
        $departments = Department::all();
        return view('admin.students.index', compact('students', 'faculties', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'reg_number' => 'required|string|unique:students,reg_number',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'required|exists:departments,id',
            'fingerprint_id' => 'required|integer|unique:students,fingerprint_id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('student_photos', 'public');
        }

        Student::create($data);

        return redirect()->back()->with('success', 'Student registered successfully.');
    }

    public function update(Request $request, Student $student)
    {
        try {
            $data = $request->validate([
                'full_name' => 'required|string|max:255',
                'reg_number' => 'required|string|unique:students,reg_number,' . $student->id,
                'faculty_id' => 'required|exists:faculties,id',
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

    public function destroy(Student $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}
