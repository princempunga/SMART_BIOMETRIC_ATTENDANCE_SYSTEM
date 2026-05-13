@extends('layouts.dean')
@section('page_title', 'Analysis Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-[#0F172A] text-lg">Academic Analysis Reports</h3>
            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold italic">Semester Attendance Matrix & Performance Metrics</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="p-2.5 bg-slate-50 text-slate-400 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all border border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span class="text-xs font-bold uppercase tracking-widest">Export Report</span>
            </button>
        </div>
    </div>

    <!-- Course Selector Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 bg-slate-50/50 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-4 bg-blue-500 rounded-full"></div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Data Filtering</span>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Select Course Unit</label>
                    <div class="relative">
                        <select name="course_id" class="w-full pl-4 pr-10 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none text-sm font-bold text-[#334155] transition-all appearance-none cursor-pointer">
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }} — ({{ $course->course_code }})
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto bg-[#0F172A] text-white px-10 py-3.5 rounded-lg font-bold text-sm hover:bg-[#1E293B] shadow-xl shadow-slate-200 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Analyze
                </button>
            </form>
        </div>
    </div>

    @if(count($reportData) > 0)
    <!-- Summary Analysis -->
    @php
        $classAvg = count($reportData) > 0 ? array_sum(array_column($reportData, 'score_out_of_5')) / count($reportData) : 0;
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Students Analyzed</p>
                <p class="text-xl font-black text-slate-800 leading-tight">{{ count($reportData) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Class Performance</p>
                <p class="text-xl font-black text-emerald-600 leading-tight">{{ number_format($classAvg, 1) }} <span class="text-xs text-slate-300 font-bold">/ 5.0</span></p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Active Weeks</p>
                <p class="text-xl font-black text-purple-600 leading-tight">{{ count($weeksWithSessions) }} <span class="text-xs text-slate-300 font-bold">/ 16</span></p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 rounded-lg flex items-center justify-center text-rose-500 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Attendance Health</p>
                @php $avgRate = array_sum(array_column($reportData, 'attendance_rate')) / count($reportData); @endphp
                <p class="text-xl font-black {{ $avgRate >= 75 ? 'text-emerald-600' : 'text-rose-600' }} leading-tight">{{ number_format($avgRate, 0) }}%</p>
            </div>
        </div>
    </div>

    <!-- Attendance Matrix Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden print:border-none print:shadow-none">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50 print:bg-white">
            <div>
                <h3 class="font-bold text-[#0F172A]">Weekly Attendance Matrix</h3>
                <p class="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-[0.2em]">Semester Overview (W1 — W16)</p>
            </div>
            <div class="flex items-center gap-6 px-4 py-2 bg-white rounded-lg border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                    <span class="text-[9px] font-black text-slate-500 uppercase">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-rose-500 rounded-full"></div>
                    <span class="text-[9px] font-black text-slate-500 uppercase">Absent</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-slate-200 rounded-full"></div>
                    <span class="text-[9px] font-black text-slate-500 uppercase">No Session</span>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-5 sticky left-0 bg-slate-50 z-20 border-r border-slate-100 min-w-[240px]">Student Details</th>
                        @for($i = 1; $i <= 16; $i++)
                        <th class="px-3 py-5 text-center border-r border-slate-100 min-w-[50px] {{ in_array($i, $weeksWithSessions) ? 'bg-blue-50/30' : '' }}">W{{ $i }}</th>
                        @endfor
                        <th class="px-6 py-5 text-center border-l border-slate-100 bg-[#F8FAFC]">Score /5.0</th>
                        <th class="px-6 py-5 text-center bg-[#F8FAFC]">Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reportData as $data)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5 sticky left-0 bg-white group-hover:bg-slate-50 z-10 border-r border-slate-100">
                            <div class="font-bold text-[#0F172A] text-sm group-hover:text-[#2563EB] transition-colors leading-tight">{{ $data['student']->full_name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">{{ $data['student']->reg_number }}</div>
                        </td>
                        @foreach($data['weeks'] as $weekNum => $isPresent)
                        <td class="px-3 py-5 text-center border-r border-slate-100">
                            @if(in_array($weekNum, $weeksWithSessions))
                                @if($isPresent)
                                    <div class="w-6 h-6 bg-emerald-50 text-emerald-600 rounded-md flex items-center justify-center mx-auto shadow-sm border border-emerald-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="w-6 h-6 bg-rose-50 text-rose-600 rounded-md flex items-center justify-center mx-auto shadow-sm border border-rose-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-1 h-1 bg-slate-200 rounded-full mx-auto"></div>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-6 py-5 text-center border-l border-slate-100 bg-[#F8FAFC]/50">
                            @php $score = $data['score_out_of_5']; @endphp
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border {{ $score >= 4 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($score >= 2.5 ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-rose-50 text-rose-700 border-rose-100') }}">
                                <span class="text-xs font-black">{{ number_format($score, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center bg-[#F8FAFC]/50">
                            <div class="text-sm font-black {{ $data['attendance_rate'] >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ number_format($data['attendance_rate'], 0) }}%
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl p-24 text-center border border-slate-100 shadow-sm">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 2v-6m-8-5h11a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"></path></svg>
        </div>
        <h3 class="font-bold text-slate-800 text-xl mb-2">Awaiting Course Selection</h3>
        <p class="text-slate-400 text-sm max-w-xs mx-auto">Please select a course unit above to generate the attendance analysis matrix for the current semester.</p>
    </div>
    @endif
</div>

<style>
    @media print {
        .sticky { position: static !important; }
        .bg-slate-50\/50 { background-color: transparent !important; }
        .shadow-sm { box-shadow: none !important; }
        table { border: 1px solid #e2e8f0; }
        th, td { border: 1px solid #e2e8f0 !important; }
    }
</style>
@endsection
