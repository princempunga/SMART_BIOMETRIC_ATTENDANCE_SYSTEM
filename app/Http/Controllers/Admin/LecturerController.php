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
        $lecturers = User::where('role', 'lecturer')->with(['courses', 'faculty', 'department'])->get();
        return view('admin.lecturers.index', compact('lecturers'));
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
        User::create($data);
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
        return redirect()->route('admin.lecturers.index')->with('success', 'Lecturer updated successfully.');
    }

    public function destroy(User $lecturer)
    {
        $lecturer->delete();
        return redirect()->route('admin.lecturers.index')->with('success', 'Lecturer deleted successfully.');
    }
}
