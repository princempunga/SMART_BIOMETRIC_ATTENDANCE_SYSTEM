@extends('layouts.dean')
@section('page_title', 'Faculty Lecturers')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-[#0F172A] text-lg">Lecturers Management</h3>
            <p class="text-xs text-slate-400 mt-1">Manage and view teaching staff assigned to your faculty.</p>
        </div>
        <button class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Lecturer
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#F8FAFC] text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-5">Lecturer Information</th>
                        <th class="px-6 py-5">Academic Placement</th>
                        <th class="px-6 py-5">Assigned Course Units</th>
                        <th class="px-6 py-5">Contact</th>
                        <th class="px-6 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturers as $lecturer)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($lecturer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-[#0F172A] text-sm leading-tight">{{ $lecturer->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-1">ID: #{{ str_pad($lecturer->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-[#334155] text-[13px] leading-tight">{{ auth()->user()->faculty->faculty_name }}</div>
                            <div class="text-[11px] text-slate-400 mt-1 font-medium">{{ $lecturer->department->department_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                @forelse($lecturer->courseUnits as $course)
                                <span class="px-2 py-1 bg-blue-50 text-[#2563EB] text-[9px] font-bold rounded-md border border-blue-100 uppercase tracking-tighter">{{ $course->course_code }}</span>
                                @empty
                                <span class="text-slate-300 text-[10px] font-medium">—</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-slate-500 font-medium lowercase tracking-tight">{{ $lecturer->email }}</div>
                            <div class="text-[11px] font-bold mt-1 {{ $lecturer->phone ? 'text-slate-400' : 'text-[#2563EB]' }}">
                                {{ $lecturer->phone ?? 'No phone' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-slate-300 hover:text-[#2563EB] hover:bg-blue-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="text-slate-400 italic text-sm">No lecturers found in your faculty.</p>
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
