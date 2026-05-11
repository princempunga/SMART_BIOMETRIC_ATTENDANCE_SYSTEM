@extends('layouts.lecturer')

@section('page_title', 'Attendance Records')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('lecturer.attendance') }}" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Filter by Course</label>
                <select name="course_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border-slate-100 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border-slate-100 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none">
            </div>
            <button type="submit" class="w-full md:w-auto bg-[#2563EB] text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-[#1D4ED8] transition-all">Apply Filter</button>
        </form>
    </div>

    <!-- Attendance Logs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">Course Code</th>
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Clock-In</th>
                        <th class="px-6 py-4">Clock-Out</th>
                        <th class="px-6 py-4 text-center">Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#0F172A] text-sm">{{ $log->student->full_name }}</div>
                            <div class="text-[10px] text-[#94A3B8]">{{ $log->student->reg_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-[#475569]">{{ $log->session->course->course_code }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#94A3B8] font-medium">{{ $log->session->classroom->room_name }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ \Carbon\Carbon::parse($log->clock_in)->format('H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg">{{ $log->attendance_mark ?? 10 }}/10</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No attendance logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
