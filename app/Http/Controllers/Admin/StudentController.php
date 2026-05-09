<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('student_photos', 'public');
        }
        Student::create($data);
        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($student->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('student_photos', 'public');
        }
        $student->update($data);
        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
