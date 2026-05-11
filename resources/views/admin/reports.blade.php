@extends('layouts.admin')

@section('page_title', 'Reports')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Generate Report -->
    <div class="lg:col-span-1 space-y-6">
        <h3 class="font-bold text-[#0F172A] px-1">Generate New Report</h3>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Course</label>
                    <select name="course_id" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs appearance-none">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date From</label>
                        <input type="date" name="from_date" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date To</label>
                        <input type="date" name="to_date" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Report Format</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-slate-50 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-[#2563EB] has-[:checked]:bg-blue-50/30 group">
                            <input type="radio" name="format" value="pdf" class="sr-only" checked>
                            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-6 h-6 text-rose-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 18H17V16H7V18Z" fill="currentColor"/>
                                    <path d="M17 14H7V12H17V14Z" fill="currentColor"/>
                                    <path d="M7 10H11V8H7V10Z" fill="currentColor"/>
                                    <path d="M6 2C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2H6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-[9px] font-bold text-[#0F172A] uppercase tracking-wider">PDF Report</span>
                        </label>
                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-slate-50 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-[#2563EB] has-[:checked]:bg-blue-50/30 group">
                            <input type="radio" name="format" value="excel" class="sr-only">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 4C4 2.89543 4.89543 2 6 2H14L20 8V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 13H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 13V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 13V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-[9px] font-bold text-[#0F172A] uppercase tracking-wider">Excel Sheet</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Generate Report</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Recent Exports -->
    <div class="lg:col-span-2 space-y-6">
        <h3 class="font-bold text-[#0F172A] px-1">Recent Exports</h3>
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Report Name</th>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Generated At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($exports as $export)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <!-- Placeholder row -->
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-sm text-slate-400 italic">No reports have been exported yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
