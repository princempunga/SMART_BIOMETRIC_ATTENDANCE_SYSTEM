@extends('layouts.admin')

@section('header', 'Lecturer Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Session Control -->
    <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Start New Session</h3>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Select Course</label>
                <select class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    <option>Select Course...</option>
                    <option>CS101 - Introduction to Programming</option>
                    <option>CS202 - Database Systems</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Classroom</label>
                <select class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    <option>Select Room...</option>
                    <option>Room 101 - Block A</option>
                    <option>Lab 3 - IT Center</option>
                </select>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                Generate OTP & Start
            </button>
        </form>

        <div class="mt-8 p-6 bg-slate-900 rounded-2xl text-center">
            <p class="text-slate-400 text-sm mb-2 uppercase tracking-widest font-bold">Session OTP</p>
            <p class="text-4xl font-bold text-white tracking-widest">8429</p>
            <p class="text-xs text-slate-500 mt-4">Enter this code on the classroom device</p>
        </div>
    </div>

    <!-- Live Attendance -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Live Attendance</h3>
                <p class="text-sm text-slate-500">Course: CS101 | Room: 101</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-bold text-blue-600">24</span>
                <span class="text-slate-400">/ 45</span>
                <p class="text-xs text-slate-500">Students Present</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-sm uppercase tracking-wider">
                        <th class="pb-4 font-medium">Student</th>
                        <th class="pb-4 font-medium">Reg Number</th>
                        <th class="pb-4 font-medium">Clock-In</th>
                        <th class="pb-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="text-slate-700">
                        <td class="py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-xs font-bold">JD</div>
                                <span class="font-medium text-sm">John Doe</span>
                            </div>
                        </td>
                        <td class="py-4 text-sm">CS/2024/001</td>
                        <td class="py-4 text-sm">10:05:12 AM</td>
                        <td class="py-4">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md">Present</span>
                        </td>
                    </tr>
                    <tr class="text-slate-700">
                        <td class="py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-xs font-bold">SM</div>
                                <span class="font-medium text-sm">Sarah Miller</span>
                            </div>
                        </td>
                        <td class="py-4 text-sm">CS/2024/023</td>
                        <td class="py-4 text-sm">10:08:45 AM</td>
                        <td class="py-4">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md">Present</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
