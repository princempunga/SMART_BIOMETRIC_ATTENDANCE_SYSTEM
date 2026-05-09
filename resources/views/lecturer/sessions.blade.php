@extends('layouts.lecturer')

@section('page_title', 'Classroom Sessions')

@section('content')
<div class="space-y-6">
    <!-- Session Control -->
    <div class="bg-[#0F172A] p-8 rounded-2xl shadow-xl text-white flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-[#2563EB]/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex-1">
            <h3 class="text-2xl font-bold mb-2">Ready for Class?</h3>
            <p class="text-slate-400 text-sm leading-relaxed max-w-md italic">Start a session to enable biometric enrollment for your students in the selected classroom.</p>
        </div>
        
        <form action="#" method="POST" class="relative z-10 flex flex-col md:flex-row items-end gap-4 w-full md:w-auto">
            @csrf
            <div class="w-full md:w-56">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                <select name="course_id" required class="w-full bg-[#1E293B] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563EB] outline-none">
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Room</label>
                <select name="classroom_id" required class="w-full bg-[#1E293B] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563EB] outline-none">
                    @foreach($classrooms as $room)
                    <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Start Class</span>
            </button>
        </form>
    </div>

    <!-- Sessions History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-[#0F172A]">Recent Sessions History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Started At</th>
                        <th class="px-6 py-4">Ended At</th>
                        <th class="px-6 py-4">OTP Code</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-[#0F172A] text-sm">{{ $session->course->course_code }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ $session->classroom->room_name }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $session->session_start->format('M d, H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $session->session_end ? $session->session_end->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-[#2563EB] font-bold">{{ $session->otp }}</td>
                        <td class="px-6 py-4 text-center">
                            @if(!$session->session_end)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                Live
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-50 text-slate-400 border border-slate-100 uppercase tracking-widest">
                                Ended
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No sessions recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sessions->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
