@extends('layouts.lecturer')

@section('page_title', 'My Assigned Courses')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($courses as $course)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6 bg-slate-50 border-b border-slate-100">
            <div class="flex justify-between items-start mb-4">
                <span class="px-2.5 py-1 bg-[#2563EB]/10 text-[#2563EB] text-[10px] font-bold rounded-lg uppercase tracking-wider">{{ $course->course_code }}</span>
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <h4 class="font-bold text-[#0F172A] text-lg leading-tight">{{ $course->course_name }}</h4>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-medium">Total Sessions</span>
                <span class="font-bold text-[#0F172A]">{{ $course->sessions_count }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-medium">Avg Attendance</span>
                <span class="font-bold text-[#10B981]">82%</span>
            </div>
            
            <div class="pt-2">
                <a href="{{ route('lecturer.sessions') }}?course={{ $course->id }}" class="w-full flex items-center justify-center gap-2 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors uppercase tracking-widest">
                    <span>View Sessions</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center">
        <p class="text-slate-400 italic">You have no courses assigned to you at the moment.</p>
    </div>
    @endforelse
</div>
@endsection
