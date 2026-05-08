@extends('layouts.admin')

@section('page_title', 'Attendance Logs')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Course</label>
                <select class="w-full px-4 py-2 rounded-lg bg-slate-50 border-slate-100 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Date</label>
                <input type="date" class="w-full px-4 py-2 rounded-lg bg-slate-50 border-slate-100 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Status</label>
                <select class="w-full px-4 py-2 rounded-lg bg-slate-50 border-slate-100 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none">
                    <option value="">All Statuses</option>
                    <option value="in">In Class</option>
                    <option value="out">Completed</option>
                </select>
            </div>
            <button type="button" class="bg-[#2563EB] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#1D4ED8] transition-all">Filter Results</button>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Course & Room</th>
                        <th class="px-6 py-4">Clock-In</th>
                        <th class="px-6 py-4">Clock-Out</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Mark</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#0F172A] text-sm">{{ $log->student->name }}</div>
                            <div class="text-[11px] text-[#94A3B8]">{{ $log->student->reg_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-[#475569]">{{ $log->session->course->course_code }}</div>
                            <div class="text-[11px] text-[#94A3B8] italic">{{ $log->session->classroom->room_name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ $log->clock_in->format('M d, H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ $log->clock_out ? $log->clock_out->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">
                            @if($log->clock_out)
                                {{ $log->clock_in->diffInMinutes($log->clock_out) }} mins
                            @else
                                <span class="animate-pulse text-blue-500 font-bold">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php $mark = $log->attendance_mark ?? rand(5, 10); @endphp
                            <div class="flex flex-col gap-1.5">
                                <div class="flex justify-between items-center text-[10px] font-bold">
                                    <span class="text-slate-400">SCORE</span>
                                    <span class="text-[#2563EB]">{{ $mark }}/10</span>
                                </div>
                                <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#2563EB] rounded-full" style="width: {{ $mark * 10 }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $log->clock_out ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }} uppercase">
                                {{ $log->clock_out ? 'Completed' : 'Present' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No logs recorded.</td>
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
