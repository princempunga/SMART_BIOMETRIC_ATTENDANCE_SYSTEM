@extends('layouts.lecturer')

@section('page_title', 'Lecturer Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- My Courses -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#2563EB] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Assigned Courses</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['courses'] }}</h3>
                <span class="text-[10px] font-bold text-[#2563EB] mt-2 inline-block bg-blue-50 px-2 py-0.5 rounded">Active Term</span>
            </div>
            <div class="w-10 h-10 bg-[#2563EB]/10 rounded-full flex items-center justify-center text-[#2563EB]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#F59E0B] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Live Sessions</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['active_sessions'] }}</h3>
                <span class="text-[10px] font-bold text-[#F59E0B] mt-2 inline-block bg-orange-50 px-2 py-0.5 rounded uppercase tracking-wider">In Progress</span>
            </div>
            <div class="w-10 h-10 bg-[#F59E0B]/10 rounded-full flex items-center justify-center text-[#F59E0B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#10B981] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Average Attendance</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['avg_attendance'] }}</h3>
                <span class="text-[10px] font-bold text-[#10B981] mt-2 inline-block bg-emerald-50 px-2 py-0.5 rounded">+2.4% This Week</span>
            </div>
            <div class="w-10 h-10 bg-[#10B981]/10 rounded-full flex items-center justify-center text-[#10B981]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>

        <!-- Total Records -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#8B5CF6] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Total Attendance Logs</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">1,248</h3>
                <span class="text-[10px] font-bold text-[#8B5CF6] mt-2 inline-block bg-purple-50 px-2 py-0.5 rounded">All Sessions</span>
            </div>
            <div class="w-10 h-10 bg-[#8B5CF6]/10 rounded-full flex items-center justify-center text-[#8B5CF6]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Sessions & Recent Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Start Session Card -->
        <div class="lg:col-span-2 bg-[#0F172A] rounded-2xl p-8 shadow-xl text-white relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#2563EB]/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <h4 class="text-xl font-bold mb-2">Classroom Session</h4>
                <p class="text-slate-400 text-xs mb-8 leading-relaxed italic">Begin a new biometric tracking session for your current class.</p>
                
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Select Course</label>
                            <select name="course_id" class="w-full bg-[#1E293B] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563EB] outline-none">
                                <option value="" disabled selected>Choose a course...</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Select Classroom</label>
                            <select name="classroom_id" class="w-full bg-[#1E293B] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563EB] outline-none">
                                <option value="" disabled selected>Choose a room...</option>
                                <option value="1">Lab 01 (ESP32_L1)</option>
                                <option value="2">Lecture Hall 4 (ESP32_H4)</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-3 mt-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>Start Session</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-[#0F172A]">Recent Class Attendance</h3>
                <button class="text-xs font-bold text-[#2563EB] uppercase hover:underline">View All</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4 text-right">Mark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0F172A] text-sm">{{ $log->student->name }}</div>
                                <div class="text-[10px] text-[#94A3B8]">{{ $log->student->reg_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-[#475569]">{{ $log->session->course->course_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ $log->clock_in->format('H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs font-bold text-[#2563EB] bg-blue-50 px-2 py-1 rounded">{{ $log->attendance_mark ?? 10 }}/10</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic text-sm">No recent attendance recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
