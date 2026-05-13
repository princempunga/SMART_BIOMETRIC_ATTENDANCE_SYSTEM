@extends('layouts.dean')
@section('page_title', 'Faculty Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Faculty Banner -->
    <div class="bg-[#0F172A] rounded-2xl p-8 text-white flex items-center justify-between shadow-xl shadow-slate-900/40 relative overflow-hidden">
        <!-- Abstract background pattern -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        
        <div class="relative z-10">
            <p class="text-[#2563EB] text-[10px] font-bold uppercase tracking-[0.3em] mb-3">Academic Overview</p>
            <h1 class="text-3xl font-bold tracking-tight mb-2">{{ $faculty->faculty_name ?? 'Your Faculty' }}</h1>
            <div class="flex items-center gap-4 text-slate-400 text-sm font-medium">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    2025-2026
                </span>
                <span class="w-1.5 h-1.5 bg-slate-700 rounded-full"></span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Semester 1
                </span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#2563EB]">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Students</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['students'] }}</h3>
            <p class="text-[#2563EB] text-xs font-bold mt-2">In your faculty</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#2563EB]">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Lecturers</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['lecturers'] }}</h3>
            <p class="text-[#2563EB] text-xs font-bold mt-2">Teaching staff</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Departments</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['departments'] }}</h3>
            <p class="text-emerald-500 text-xs font-bold mt-2">Academic units</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Courses</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['courses'] }}</h3>
            <p class="text-amber-500 text-xs font-bold mt-2">This semester</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-rose-500">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Attendance Rate</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['attendance_rate'] }}</h3>
            <p class="text-rose-500 text-xs font-bold mt-2">Avg. sessions</p>
        </div>
    </div>

    <!-- Two-column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        <!-- Recent Attendance -->
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Recent Attendance — {{ $faculty->faculty_name ?? '' }}</h3>
                <a href="{{ route('dean.attendance') }}" class="text-xs font-bold text-[#2563EB] hover:underline uppercase">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Room</th>
                            <th class="px-6 py-4">Clock-In</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentAttendance as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800 text-sm">{{ $log->student->full_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $log->student->reg_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $log->session->course->course_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $log->session->classroom->room_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $log->clock_in->format('H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $log->clock_out ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $log->clock_out ? 'COMPLETED' : 'IN CLASS' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">No recent logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Departments Overview -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Departments</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $departments->count() }} units</span>
            </div>
            <div class="p-4 space-y-3">
                @forelse($departments as $dept)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-[#2563EB] transition-colors">
                            <svg class="w-4 h-4 text-[#2563EB] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-700">{{ $dept->department_name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $dept->students_count }} students</div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-[#2563EB] bg-blue-50 px-2 py-1 rounded-lg">{{ $dept->students_count }}</span>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-8 italic">No departments found.</p>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div class="p-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('dean.students') }}" class="flex items-center gap-3 p-3 bg-[#2563EB] text-white rounded-xl hover:bg-[#1D4ED8] transition-colors font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"></path></svg>
                    View All Students
                </a>
                <a href="{{ route('dean.reports') }}" class="flex items-center gap-3 p-3 bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition-colors font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Analysis Reports
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
