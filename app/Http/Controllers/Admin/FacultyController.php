<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();
        return view('admin.faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('admin.faculties.create');
    }

    public function store(StoreFacultyRequest $request)
    {
        Faculty::create($request->validated());
        return redirect()->route('admin.faculties.index')->with('success', 'Faculty created successfully.');
    }

    public function edit(Faculty $faculty)
    {
        return view('admin.faculties.edit', compact('faculty'));
    }

    public function update(UpdateFacultyRequest $request, Faculty $faculty)
    {
        $faculty->update($request->validated());
        return redirect()->route('admin.faculties.index')->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return redirect()->route('admin.faculties.index')->with('success', 'Faculty deleted successfully.');
    }
}
