@extends('layouts.dean')
@section('page_title', 'Attendance Logs')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <form method="GET" class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex flex-wrap gap-4 items-end">
        <div>
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <button type="submit" class="bg-purple-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-purple-700 transition-colors">Filter</button>
        <a href="{{ route('dean.attendance') }}" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-slate-200 transition-colors">Reset</a>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Attendance Logs — Faculty Only</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Clock In</th>
                        <th class="px-6 py-4">Clock Out</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 text-sm">{{ $log->student->full_name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $log->student->reg_number }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->session->course->course_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->session->classroom->room_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->clock_in->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->clock_out ? $log->clock_out->format('H:i') : '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $log->duration ? floor($log->duration / 60) . 'h ' . ($log->duration % 60) . 'm' : '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $log->clock_out ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ $log->clock_out ? 'COMPLETED' : 'IN CLASS' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-16 text-center text-slate-400 italic">No attendance logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
