<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\UpdateLecturerRequest;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = User::where('role', 'lecturer')->with(['courseUnits', 'faculty', 'department'])->get();
        $faculties = \App\Models\Faculty::all();
        $departments = \App\Models\Department::all();
        $allCourseUnits = \App\Models\CourseUnit::all();
        return view('admin.lecturers.index', compact('lecturers', 'faculties', 'departments', 'allCourseUnits'));
    }

    public function create()
    {
        return view('admin.lecturers.create');
    }

    public function store(StoreLecturerRequest $request)
    {
        $data = $request->validated();
        $data['role'] = 'lecturer';
        $data['password'] = Hash::make($data['password']);
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('lecturer_photos', 'public');
        }
        
        $lecturer = User::create($data);

        // Assign course units if selected
        if ($request->has('course_unit_ids')) {
            $lecturer->courseUnits()->sync($request->course_unit_ids);
        }

        return redirect()->route('admin.lecturers.index')->with('success', 'Lecturer created successfully.');
    }

    public function edit(User $lecturer)
    {
        return view('admin.lecturers.edit', compact('lecturer'));
    }

    public function update(UpdateLecturerRequest $request, User $lecturer)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if ($request->hasFile('profile_photo')) {
            if ($lecturer->profile_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($lecturer->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('lecturer_photos', 'public');
        }
        
        $lecturer->update($data);

        // Reassign course units
        if ($request->has('course_unit_ids')) {
            $lecturer->courseUnits()->sync($request->course_unit_ids);
        } else {
            $lecturer->courseUnits()->detach();
        }

        return redirect()->route('admin.lecturers.index')->with('success', 'Lecturer updated successfully.');
    }

    public function destroy(User $lecturer)
    {
        $lecturer->delete();
        return redirect()->route('admin.lecturers.index')->with('success', 'Lecturer deleted successfully.');
    }
}
