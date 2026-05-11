@extends('layouts.dean')
@section('page_title', 'Faculty Lecturers')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Lecturers in Your Faculty</h3>
            <p class="text-xs text-slate-400 mt-1">{{ $lecturers->count() }} lecturer(s) assigned</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Lecturer</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Courses</th>
                        <th class="px-6 py-4">Phone</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturers as $lecturer)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm">
                                    {{ strtoupper(substr($lecturer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800 text-sm">{{ $lecturer->name }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">Lecturer</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $lecturer->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $lecturer->department->department_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @forelse($lecturer->courseUnits as $course)
                            <span class="inline-block bg-purple-50 text-purple-600 text-[10px] font-bold px-2 py-0.5 rounded mr-1 mb-1">{{ $course->course_code }}</span>
                            @empty
                            <span class="text-slate-400 text-xs">—</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $lecturer->phone ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-16 text-center text-slate-400 italic">No lecturers found in your faculty.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
