@extends('layouts.dean')
@section('page_title', 'Analysis Reports')

@section('content')
<div class="space-y-6">

    <!-- Course Selector -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[260px]">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Select Course</label>
                <select name="course_id" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                        {{ $course->course_name }} ({{ $course->course_code }})
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-purple-500/20 hover:bg-purple-700 transition-all">
                Analyze
            </button>
        </form>
    </div>

    @if(count($reportData) > 0)
    <!-- Summary Cards -->
    @php
        $classAvg = count($reportData) > 0 ? array_sum(array_column($reportData, 'score_out_of_5')) / count($reportData) : 0;
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Students</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ count($reportData) }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Class Average</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($classAvg, 1) }} <span class="text-base text-slate-400 font-normal">/ 5</span></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Weeks Recorded</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ count($weeksWithSessions) }} <span class="text-base text-slate-400 font-normal">/ 16</span></p>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800">Weekly Attendance Matrix</h3>
                <p class="text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-widest italic">Full Semester View (Week 1 - 16)</p>
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
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 sticky left-0 bg-slate-50 z-10 border-r border-slate-100">Student</th>
                        @for($i = 1; $i <= 16; $i++)
                        <th class="px-3 py-4 text-center border-r border-slate-100">W{{ $i }}</th>
                        @endfor
                        <th class="px-6 py-4 text-center bg-blue-50/50">Time</th>
                        <th class="px-6 py-4 text-center bg-purple-50">Score /5</th>
                        <th class="px-6 py-4 text-center">Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reportData as $data)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 sticky left-0 bg-white z-10 border-r border-slate-100">
                            <div class="font-semibold text-slate-800 text-sm">{{ $data['student']->full_name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $data['student']->reg_number }}</div>
                        </td>
                        @foreach($data['weeks'] as $weekNum => $isPresent)
                        <td class="px-3 py-4 text-center border-r border-slate-100">
                            @if(in_array($weekNum, $weeksWithSessions))
                                @if($isPresent)
                                    <div class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="w-6 h-6 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-6 h-6 border border-dashed border-slate-200 rounded-lg mx-auto"></div>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-6 py-4 text-center text-sm text-slate-500 bg-blue-50/30">
                            {{ floor($data['total_duration'] / 60) }}h
                        </td>
                        <td class="px-6 py-4 text-center bg-purple-50/50">
                            @php $score = $data['score_out_of_5']; @endphp
                            <span class="font-bold text-sm {{ $score >= 4 ? 'text-emerald-600' : ($score >= 2.5 ? 'text-amber-500' : 'text-red-500') }}">
                                {{ number_format($score, 1) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold px-2 py-1 rounded-full {{ $data['attendance_rate'] >= 75 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                                {{ number_format($data['attendance_rate'], 0) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl p-16 text-center border border-slate-100 shadow-sm">
        <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="font-bold text-slate-700 text-lg mb-2">No data yet</h3>
        <p class="text-slate-400 text-sm">Select a course and click Analyze to see the attendance report.</p>
    </div>
    @endif

</div>
@endsection
