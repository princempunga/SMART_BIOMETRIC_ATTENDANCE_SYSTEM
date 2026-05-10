@extends('layouts.lecturer')

@section('page_title', 'Lecturer Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header with Week Info -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h2 class="text-2xl font-bold text-[#0F172A]">Welcome back, {{ Auth::user()->name }}</h2>
            <p class="text-slate-500 text-sm mt-1">Academic Year 2026 • Semester 1</p>
        </div>
        <div class="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
            <span class="text-blue-700 font-bold text-sm">WEEK {{ $stats['current_week'] }}</span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- My Courses -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#2563EB] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Assigned Courses</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['courses'] }}</h3>
                <span class="text-[10px] font-bold text-[#2563EB] mt-2 inline-block bg-blue-50 px-2 py-0.5 rounded uppercase tracking-wider">Active</span>
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
                <p class="text-[#94A3B8] text-sm font-medium">Total Logs</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">1,248</h3>
                <span class="text-[10px] font-bold text-[#8B5CF6] mt-2 inline-block bg-purple-50 px-2 py-0.5 rounded uppercase tracking-wider">Verified</span>
            </div>
            <div class="w-10 h-10 bg-[#8B5CF6]/10 rounded-full flex items-center justify-center text-[#8B5CF6]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Active Session & Timetable -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Start/Active Session Card -->
        <div class="lg:col-span-2 space-y-6">
            @if($activeSession)
            <div class="bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] rounded-2xl p-8 shadow-xl text-white relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $activeSession->status }} Session</span>
                        <div class="animate-pulse flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Live Now</span>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold mb-1">{{ $activeSession->course->course_name }}</h4>
                    <p class="text-blue-100 text-xs mb-8">{{ $activeSession->classroom->room_name }} • Week {{ $activeSession->week_number }}</p>
                    
                    <a href="{{ route('lecturer.sessions.active', $activeSession) }}" class="w-full bg-white text-[#2563EB] font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-3 hover:bg-blue-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span>View Active Session</span>
                    </a>
                </div>
            </div>
            @else
            <div class="bg-[#0F172A] rounded-2xl p-8 shadow-xl text-white relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#2563EB]/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <h4 class="text-xl font-bold mb-2">Start Session</h4>
                    <p class="text-slate-400 text-xs mb-8 leading-relaxed italic">Select your scheduled class to begin attendance tracking.</p>
                    
                    <form action="{{ route('lecturer.sessions.start') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Select Course</label>
                                <select name="course_id" required class="w-full bg-[#1E293B] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563EB] outline-none">
                                    <option value="" disabled selected>Choose a course...</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Select Classroom</label>
                                <select name="classroom_id" required class="w-full bg-[#1E293B] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563EB] outline-none">
                                    <option value="" disabled selected>Choose a room...</option>
                                    @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->room_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-3 mt-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Initialize Session</span>
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Today's Timetable -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-[#0F172A]">Today's Timetable</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($todayTimetable as $slot)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-50 hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex flex-col items-center justify-center text-[#2563EB]">
                            <span class="text-[10px] font-bold uppercase">{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i') }}</span>
                            <span class="text-[8px] font-bold opacity-60">{{ \Carbon\Carbon::parse($slot->start_time)->format('A') }}</span>
                        </div>
                        <div class="flex-1">
                            <h5 class="font-bold text-sm text-[#0F172A]">{{ $slot->course->course_code }}</h5>
                            <p class="text-[10px] text-slate-500">{{ $slot->classroom->room_name }} • {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</p>
                        </div>
                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-bold rounded uppercase">Upcoming</span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-slate-400 text-xs italic">No scheduled classes for today.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-[#0F172A]">Live Attendance Stream</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('lecturer.reports') }}" class="text-xs font-bold text-emerald-600 uppercase hover:underline">Analysis Report</a>
                        <a href="{{ route('lecturer.attendance') }}" class="text-xs font-bold text-[#2563EB] uppercase hover:underline">View All Logs</a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Student</th>
                                <th class="px-6 py-4">Course</th>
                                <th class="px-6 py-4">Time</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentLogs as $log)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0F172A] text-sm">{{ $log->student->full_name }}</div>
                                    <div class="text-[10px] text-[#94A3B8]">{{ $log->student->reg_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-[#475569]">{{ $log->session->course->course_code }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ \Carbon\Carbon::parse($log->clock_in)->format('H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-[10px] font-bold text-[#10B981] bg-emerald-50 px-2.5 py-1 rounded-full uppercase">{{ $log->status ?? 'Present' }}</span>
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

            <!-- Testing Quick Links -->
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('lecturer.test-environment') }}" class="p-6 bg-slate-50 rounded-2xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                    <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-slate-600 mb-4 group-hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.477 2.387a2 2 0 00.547 2.022 2 2 0 002.022.547l2.387-.477a2 2 0 001.414-1.96l-.477-2.387a2 2 0 00-2.022-.547z"></path></svg>
                    </div>
                    <h5 class="font-bold text-sm text-slate-800">Test Simulator</h5>
                    <p class="text-[10px] text-slate-500 mt-1">Simulate biometric scans for testing.</p>
                </a>
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 opacity-60">
                    <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-slate-600 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h5 class="font-bold text-sm text-slate-800">Reports</h5>
                    <p class="text-[10px] text-slate-500 mt-1">Coming soon...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
