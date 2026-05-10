@extends('layouts.lecturer')

@section('page_title', 'Attendance Analysis')

@section('content')
<div class="space-y-6">
    <!-- Filter & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('lecturer.reports') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
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
                <button type="submit" class="bg-[#2563EB] text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 hover:bg-[#1D4ED8] transition-all">
                    Generate Report
                </button>
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
    </div>

    <!-- Analysis Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-bold text-[#0F172A]">Weekly Attendance Matrix</h3>
                <p class="text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-widest italic">Week 1 to Week 16 • 75% Requirement Threshold</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-emerald-500 rounded-sm"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-slate-200 rounded-sm"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Absent</span>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 sticky left-0 bg-slate-50 z-10 border-r border-slate-100">Student Information</th>
                        @for($i = 1; $i <= 16; $i++)
                        <th class="px-3 py-4 text-center border-r border-slate-100">W{{ $i }}</th>
                        @endfor
                        <th class="px-6 py-4 text-center bg-blue-50/50">Total Time</th>
                        <th class="px-6 py-4 text-center bg-blue-50/50">Rate</th>
                        <th class="px-6 py-4 text-right bg-blue-50/50">Eligibility</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($reportData as $data)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 sticky left-0 bg-white z-10 border-r border-slate-100 min-w-[200px]">
                            <div class="font-bold text-[#0F172A]">{{ $data['student']->full_name }}</div>
                            <div class="text-[10px] text-[#94A3B8] font-mono">{{ $data['student']->reg_number }}</div>
                        </td>
                        
                        @foreach($data['weeks'] as $weekNum => $isPresent)
                        <td class="px-3 py-4 text-center border-r border-slate-100">
                            @if($isPresent)
                                <div class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mx-auto shadow-sm shadow-emerald-500/10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            @else
                                <div class="w-6 h-6 bg-slate-100 text-slate-300 rounded-lg flex items-center justify-center mx-auto opacity-50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                            @endif
                        </td>
                        @endforeach

                        <td class="px-6 py-4 text-center font-mono font-bold text-[#2563EB] bg-blue-50/20">
                            {{ floor($data['total_duration'] / 60) }}h {{ $data['total_duration'] % 60 }}m
                        </td>
                        
                        <td class="px-6 py-4 text-center bg-blue-50/20">
                            <div class="flex flex-col items-center">
                                <span class="font-bold {{ $data['attendance_rate'] >= 75 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ number_format($data['attendance_rate'], 1) }}%
                                </span>
                                <div class="w-16 h-1 bg-slate-200 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full {{ $data['attendance_rate'] >= 75 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $data['attendance_rate'] }}%"></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right bg-blue-50/20">
                            @if($data['attendance_rate'] >= 75)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full uppercase tracking-widest border border-emerald-100 shadow-sm shadow-emerald-500/5">Eligible</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-widest border border-red-100 shadow-sm shadow-red-500/5">Ineligible</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="21" class="px-6 py-20 text-center text-slate-400 italic font-medium">
                            No students found for this course.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
