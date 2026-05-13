@extends('layouts.lecturer')

@section('page_title', 'Attendance Analysis')

@push('styles')
<style>
    @media print {
        .no-print, nav, aside, .sidebar-link, header { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; }
        .bg-white { border: none !important; }
        .shadow-sm, .shadow-xl { shadow: none !important; }
        body { background: white !important; }
        .rounded-2xl { border-radius: 0 !important; }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Filter & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('lecturer.reports') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4 no-print">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Select Course for Analysis</label>
                    <select name="course_id" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 text-sm font-bold text-[#0F172A] focus:ring-2 focus:ring-blue-500/10 outline-none">
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }} ({{ $course->course_code }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-[#2563EB] text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 hover:bg-[#1D4ED8] transition-all">
                        Analyze
                    </button>
                    <a href="{{ route('lecturer.reports.download', ['course_id' => $selectedCourseId]) }}" class="bg-[#0F172A] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-slate-900/20 hover:bg-[#1E293B] transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-6 rounded-2xl shadow-xl text-white">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Students</p>
            <h3 class="text-3xl font-bold">{{ count($reportData) }}</h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded w-fit uppercase">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                Active Enrollment
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] p-6 rounded-2xl shadow-xl text-white">
            <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-1">Class Average (/5)</p>
            <h3 class="text-3xl font-bold">
                {{ count($reportData) > 0 ? number_format(array_sum(array_column($reportData, 'score_out_of_5')) / count($reportData), 1) : '0.0' }}
            </h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-white bg-white/10 px-2 py-1 rounded w-fit uppercase">
                Academic Performance
            </div>
        </div>
    </div>

    <!-- Analysis Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-bold text-[#0F172A]">Weekly Attendance Matrix</h3>
                <p class="text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-widest italic">Full Semester View (Week 1 - 16) • 75% Requirement Threshold</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-emerald-500 rounded-sm"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-red-500 rounded-sm"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Absent</span>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto custom-scrollbar shadow-inner bg-slate-50/10">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="bg-slate-50 text-[9px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-4 sticky left-0 bg-slate-50 z-20 border-r border-slate-100 shadow-[2px_0_5px_rgba(0,0,0,0.05)] min-w-[180px]">Student Information</th>
                        @for($i = 1; $i <= 16; $i++)
                        <th class="px-1 py-4 text-center border-r border-slate-100 min-w-[35px]">{{ $i }}</th>
                        @endfor
                        <th class="px-3 py-4 text-center bg-blue-50/80 border-l border-slate-100">Time</th>
                        <th class="px-3 py-4 text-center bg-blue-50/80">Rate</th>
                        <th class="px-3 py-4 text-right bg-blue-50/80">Result</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[13px]">
                    @forelse($reportData as $data)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-4 py-3 sticky left-0 bg-white z-10 border-r border-slate-100 group-hover:bg-slate-50 transition-colors shadow-[2px_0_5px_rgba(0,0,0,0.02)]">
                            <div class="font-bold text-[#0F172A] whitespace-nowrap">{{ $data['student']->full_name }}</div>
                            <div class="text-[9px] text-[#94A3B8] font-mono">{{ $data['student']->reg_number }}</div>
                        </td>
                        
                        @foreach($data['weeks'] as $weekNum => $isPresent)
                        <td class="px-1 py-3 text-center border-r border-slate-100">
                            @if(in_array($weekNum, $weeksWithSessions))
                                @if($isPresent)
                                    <div class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-md flex items-center justify-center mx-auto shadow-sm shadow-emerald-500/10">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="w-5 h-5 bg-red-100 text-red-600 rounded-md flex items-center justify-center mx-auto shadow-sm shadow-red-500/10">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-5 h-5 border border-dashed border-slate-200 rounded-md mx-auto opacity-20"></div>
                            @endif
                        </td>
                        @endforeach

                        <td class="px-3 py-3 text-center font-mono font-bold text-[#2563EB] bg-blue-50/30 border-l border-slate-100 whitespace-nowrap">
                            {{ floor($data['total_duration'] / 60) }}h {{ $data['total_duration'] % 60 }}m
                        </td>

                        <td class="px-6 py-4 text-center bg-emerald-50/30">
                            <div class="flex flex-col items-center">
                                <span class="text-xl font-black text-emerald-700">{{ number_format($data['score_out_of_5'], 1) }}</span>
                                <span class="text-[8px] font-bold text-emerald-600/50 uppercase tracking-tighter">Points</span>
                            </div>
                        </td>
                        
                        <td class="px-3 py-3 text-center bg-blue-50/30">
                            <span class="font-bold {{ $data['attendance_rate'] >= 75 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ number_format($data['attendance_rate'], 1) }}%
                            </span>
                        </td>

                        <td class="px-3 py-3 text-right bg-blue-50/30 whitespace-nowrap">
                            @if($data['attendance_rate'] >= 75)
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-bold rounded-full uppercase border border-emerald-100">OK</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[9px] font-bold rounded-full uppercase border border-red-100">FAIL</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="21" class="px-6 py-24 text-center text-slate-400 italic font-medium">
                            No student records available for analysis in this course.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        border: 2px solid #f1f5f9;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
