<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DeanController extends Controller
{
    public function index()
    {
        $deans = User::where('role', 'dean')->with(['faculty'])->get();
        $faculties = Faculty::all();
        return view('admin.deans.index', compact('deans', 'faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'faculty_id'    => 'required|exists:faculties,id',
            'phone'         => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'dean',
            'faculty_id'    => $request->faculty_id,
            'phone'         => $request->phone,
            'profile_photo' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Dean account created successfully.');
    }

    public function update(Request $request, User $dean)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $dean->id,
            'faculty_id'    => 'required|exists:faculties,id',
            'phone'         => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'email', 'faculty_id', 'phone');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($dean->profile_photo) {
                Storage::disk('public')->delete($dean->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $dean->update($data);

        return redirect()->back()->with('success', 'Dean account updated successfully.');
    }

    public function destroy(User $dean)
    {
        if ($dean->profile_photo) {
            Storage::disk('public')->delete($dean->profile_photo);
        }
        $dean->delete();
        return redirect()->back()->with('success', 'Dean account deleted successfully.');
    }
}
