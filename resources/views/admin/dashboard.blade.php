@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#2563EB] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Total Students</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['students'] }}</h3>
                <span class="text-xs font-bold text-[#10B981] mt-2 inline-block bg-emerald-50 px-2 py-0.5 rounded">+4.5%</span>
            </div>
            <div class="w-10 h-10 bg-[#2563EB]/10 rounded-full flex items-center justify-center text-[#2563EB]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Lecturers -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#10B981] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Total Lecturers</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['lecturers'] }}</h3>
                <span class="text-xs font-bold text-[#10B981] mt-2 inline-block bg-emerald-50 px-2 py-0.5 rounded">+2.1%</span>
            </div>
            <div class="w-10 h-10 bg-[#10B981]/10 rounded-full flex items-center justify-center text-[#10B981]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#F59E0B] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Active Sessions</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['active_sessions'] }}</h3>
                <span class="text-xs font-bold text-[#F59E0B] mt-2 inline-block bg-orange-50 px-2 py-0.5 rounded">Live Now</span>
            </div>
            <div class="w-10 h-10 bg-[#F59E0B]/10 rounded-full flex items-center justify-center text-[#F59E0B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#8B5CF6] flex justify-between items-start">
            <div>
                <p class="text-[#94A3B8] text-sm font-medium">Attendance Rate</p>
                <h3 class="text-3xl font-bold text-[#0F172A] mt-1">{{ $stats['attendance_rate'] }}</h3>
                <span class="text-xs font-bold text-[#8B5CF6] mt-2 inline-block bg-purple-50 px-2 py-0.5 rounded">Avg Week</span>
            </div>
            <div class="w-10 h-10 bg-[#8B5CF6]/10 rounded-full flex items-center justify-center text-[#8B5CF6]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tables and Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Recent Attendance -->
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-[#0F172A]">Recent Attendance</h3>
                <a href="{{ route('admin.attendance') }}" class="text-xs font-bold text-[#2563EB] hover:underline uppercase">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
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
                                <div class="font-semibold text-[#0F172A] text-sm">{{ $log->student->name }}</div>
                                <div class="text-[11px] text-[#94A3B8]">{{ $log->student->reg_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#475569]">{{ $log->session->course->course_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-[#475569]">{{ $log->session->classroom->room_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-[#475569]">{{ $log->clock_in->format('H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $log->clock_out ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $log->clock_out ? 'COMPLETED' : 'IN CLASS' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">No recent logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="font-bold text-[#0F172A] px-1">Quick Actions</h3>
            <div class="grid gap-4">
                <a href="{{ route('admin.students') }}" class="group bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between hover:bg-[#F8FAFC] transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-[#0F172A] text-sm">Add Student</div>
                            <div class="text-[11px] text-[#94A3B8]">Register new fingerprint</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <a href="{{ route('admin.lecturers') }}" class="group bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between hover:bg-[#F8FAFC] transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-[#0F172A] text-sm">Add Lecturer</div>
                            <div class="text-[11px] text-[#94A3B8]">Assign faculty account</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <a href="{{ route('admin.reports') }}" class="group bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between hover:bg-[#F8FAFC] transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-[#0F172A] text-sm">View Reports</div>
                            <div class="text-[11px] text-[#94A3B8]">Analytics & Export</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Chart Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-8">
            <h3 class="font-bold text-[#0F172A]">Attendance This Week</h3>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB]"></span>
                    <span class="text-xs font-medium text-[#94A3B8]">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#E2E8F0]"></span>
                    <span class="text-xs font-medium text-[#94A3B8]">Absent</span>
                </div>
            </div>
        </div>

        <div class="relative h-[240px] mt-4">
            <!-- Grid Lines -->
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-8">
                @foreach([100, 80, 60, 40, 20] as $level)
                <div class="flex items-center gap-4">
                    <span class="text-[11px] font-bold text-[#CBD5E1] w-8 text-right">{{ $level }}%</span>
                    <div class="flex-1 border-t border-dashed border-[#F1F5F9]"></div>
                </div>
                @endforeach
                <div class="flex items-center gap-4">
                    <span class="text-[11px] font-bold text-[#CBD5E1] w-8 text-right">0%</span>
                    <div class="flex-1 border-t border-slate-200"></div>
                </div>
            </div>

            <!-- Bars Area -->
            <div class="absolute inset-0 pl-12 pr-4 flex justify-between items-end pb-8">
                @php 
                    $data = [
                        'Mon' => 85, 'Tue' => 72, 'Wed' => 90, 
                        'Thu' => 68, 'Fri' => 78, 'Sat' => 45
                    ];
                @endphp

                @foreach($data as $day => $val)
                <div class="flex-1 flex flex-col items-center group relative h-[200px]">
                    <!-- Tooltip -->
                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-[#0F172A] text-white text-[11px] font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none shadow-xl">
                        {{ $val }}% Attendance
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-[#0F172A] rotate-45"></div>
                    </div>

                    <!-- Bar -->
                    <div class="relative w-12 flex flex-col items-center h-full">
                        <div class="absolute bottom-0 w-full rounded-t-lg transition-all duration-300 group-hover:opacity-85 group-hover:scale-y-[1.02] origin-bottom shadow-lg" 
                             style="height: {{ $val }}%; background: linear-gradient(to top, #1D4ED8, #60A5FA);">
                            <!-- Percentage Label -->
                            <span class="absolute -top-7 left-1/2 -translate-x-1/2 text-xs font-bold text-[#2563EB] whitespace-nowrap">{{ $val }}%</span>
                        </div>
                    </div>

                    <!-- Day Label -->
                    <div class="absolute -bottom-8 text-xs font-bold text-[#94A3B8]">{{ $day }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
