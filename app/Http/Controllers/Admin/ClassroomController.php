<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('admin.classrooms.create');
    }

    public function store(StoreClassroomRequest $request)
    {
        Classroom::create($request->validated());
        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        return view('admin.classrooms.edit', compact('classroom'));
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom deleted successfully.');
    }
}
