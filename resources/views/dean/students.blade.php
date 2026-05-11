@extends('layouts.dean')
@section('page_title', 'Faculty Students')

@section('content')
<div class="space-y-6">

    <!-- Filters -->
    <form method="GET" class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Search</label>
            <input name="search" value="{{ request('search') }}" placeholder="Name or Registration number..." 
                class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <div class="min-w-[200px]">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Department</label>
            <select name="department_id" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-purple-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-purple-700 transition-colors">Filter</button>
        <a href="{{ route('dean.students') }}" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-slate-200 transition-colors">Reset</a>
    </form>

    <!-- Stats Bar -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <div class="text-2xl font-bold text-slate-800">{{ $students->total() }}</div>
            <div class="text-xs text-slate-400 font-bold uppercase mt-1">Total Students</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $departments->count() }}</div>
            <div class="text-xs text-slate-400 font-bold uppercase mt-1">Departments</div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <div class="text-2xl font-bold text-emerald-600">{{ $students->currentPage() }}/{{ $students->lastPage() }}</div>
            <div class="text-xs text-slate-400 font-bold uppercase mt-1">Current Page</div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Students List</h3>
            <p class="text-xs text-slate-400 mt-1">Showing students from your faculty only</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Reg. Number</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Fingerprint ID</th>
                        <th class="px-6 py-4">Attendance Logs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $i => $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-400">{{ ($students->currentPage() - 1) * $students->perPage() + $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm">
                                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800 text-sm">{{ $student->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">{{ $student->reg_number }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $student->department->department_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $student->fingerprint_id }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-50 text-blue-600">
                                {{ $student->attendanceLogs->count() }} logs
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-16 text-center text-slate-400 italic">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
