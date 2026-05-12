@extends('layouts.admin')

@section('page_title', 'Attendance Logs')

@section('content')
<div class="space-y-6">
    <!-- SUMMARY BAR -->
    <div class="flex items-center gap-6 bg-white rounded-full shadow-sm border border-slate-100 px-8 py-4 overflow-x-auto no-scrollbar">
        <div class="flex items-center gap-3 shrink-0">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Sessions Today</div>
            <div class="text-lg font-bold text-[#0F172A]">{{ $stats['total_sessions'] }}</div>
        </div>
        <div class="w-px h-8 bg-slate-100 shrink-0"></div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Students Present</div>
            <div class="text-lg font-bold text-[#0F172A]">{{ $stats['total_present'] }}</div>
        </div>
        <div class="w-px h-8 bg-slate-100 shrink-0"></div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Avg Attendance Rate</div>
            <div class="text-lg font-bold text-[#0F172A]">{{ $stats['avg_rate'] }}%</div>
        </div>
        <div class="w-px h-8 bg-slate-100 shrink-0"></div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Right Now</div>
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-lg shadow-emerald-500/50"></div>
                <div class="text-lg font-bold text-[#0F172A]">{{ $stats['active_now'] }}</div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-end mb-6">
            <h4 class="text-xs font-bold text-[#0F172A] uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Parameters
            </h4>
            <div class="flex gap-2">
                <button onclick="toggleAll(true)" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest px-3 py-1.5 bg-blue-50 rounded-md transition-all">Expand All</button>
                <button onclick="toggleAll(false)" class="text-[10px] font-bold text-slate-500 hover:text-slate-600 uppercase tracking-widest px-3 py-1.5 bg-slate-50 rounded-md transition-all">Collapse All</button>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.attendance') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Faculty</label>
                <select name="faculty_id" id="faculty_id" onchange="loadDepartments(this.value)" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 text-xs font-bold focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    <option value="">All Faculties</option>
                    @foreach($allFaculties as $fac)
                    <option value="{{ $fac->id }}" {{ request('faculty_id') == $fac->id ? 'selected' : '' }}>{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Department</label>
                <select name="department_id" id="department_id" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 text-xs font-bold focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    <option value="">All Departments</option>
                    {{-- Dynamically populated --}}
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Course Unit</label>
                <select name="course_id" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 text-xs font-bold focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    <option value="">All Courses</option>
                    @foreach($allCourses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 text-xs font-bold focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-2.5 rounded-lg shadow-lg shadow-blue-500/20 transition-all uppercase tracking-widest text-[10px]">Apply Filters</button>
        </form>
    </div>

    <!-- HIERARCHICAL LOGS -->
    <div class="space-y-6" id="facultyContainer">
        @forelse($faculties as $faculty)
        <!-- LEVEL 1: FACULTY CARD -->
        <div class="faculty-card bg-white rounded-xl shadow-sm border-l-4 border-[#2563EB] overflow-hidden transition-all duration-300">
            <div class="p-6 flex justify-between items-center cursor-pointer hover:bg-slate-50/50" onclick="toggleSection('faculty-{{ $faculty->id }}')">
                <div>
                    <h3 class="text-[18px] font-bold text-[#0F172A]">{{ $faculty->faculty_name }}</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">
                        Total students present today: 
                        <span class="text-blue-600 font-bold">
                            @php
                                $facPresent = 0;
                                foreach($faculty->departments as $dept) {
                                    foreach($dept->courseUnits as $unit) {
                                        foreach($unit->sessions as $sess) {
                                            $facPresent += $sess->attendanceLogs->count();
                                        }
                                    }
                                }
                                echo $facPresent;
                            @endphp
                        </span>
                    </p>
                </div>
                <button class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-transform duration-300 transform" id="arrow-faculty-{{ $faculty->id }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <!-- LEVEL 2: DEPARTMENT SECTION -->
            <div id="content-faculty-{{ $faculty->id }}" class="hidden border-t border-slate-100 bg-slate-50/30 p-6 space-y-8">
                @forelse($faculty->departments as $department)
                <div class="space-y-4">
                    <div class="flex items-center gap-4 bg-[#F8FAFC] px-5 py-3 rounded-lg border border-slate-200/50">
                        <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                        <h4 class="text-sm font-semibold text-[#374151] uppercase tracking-wider">{{ $department->department_name }}</h4>
                    </div>

                    <!-- LEVEL 3: CLASSROOM SESSION CARDS -->
                    <div class="grid grid-cols-1 gap-4 pl-6">
                        @php
                            $sessionsFound = false;
                        @endphp
                        @foreach($department->courseUnits as $courseUnit)
                            @foreach($courseUnit->sessions as $session)
                            @php $sessionsFound = true; @endphp
                            <div class="session-card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                                <div class="p-5 flex flex-wrap items-center justify-between gap-6">
                                    <div class="flex items-center gap-4 min-w-[200px]">
                                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#0F172A]">{{ $session->classroom->room_name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono tracking-wider">{{ $session->classroom->room_code }}</div>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-[200px]">
                                        <div class="text-sm font-bold text-[#374151]">{{ $courseUnit->course_name }}</div>
                                        <div class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">{{ $courseUnit->course_code }}</div>
                                    </div>

                                    <div class="flex items-center gap-6 min-w-[300px]">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div class="text-xs">
                                                <div class="font-bold text-slate-600">{{ $session->lecturer->name }}</div>
                                                <div class="text-[10px] text-slate-400">{{ $session->session_start->format('h:i A') }} — {{ $session->session_end ? $session->session_end->format('h:i A') : 'Active' }}</div>
                                            </div>
                                        </div>
                                        
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'completed' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'expired' => 'bg-slate-50 text-slate-500 border-slate-100'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-widest {{ $statusColors[$session->status] ?? 'bg-slate-50 text-slate-500' }}">
                                            {{ $session->status }}
                                        </span>
                                    </div>

                                    <button onclick="toggleSection('session-{{ $session->id }}')" class="bg-slate-50 hover:bg-slate-100 text-slate-500 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all">
                                        View Students ({{ $session->attendanceLogs->count() }})
                                    </button>
                                </div>

                                <!-- STUDENT TABLE -->
                                <div id="content-session-{{ $session->id }}" class="hidden border-t border-slate-50">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left">
                                            <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                <tr>
                                                    <th class="px-8 py-3">Student Name</th>
                                                    <th class="px-8 py-3">Reg Number</th>
                                                    <th class="px-8 py-3 text-center">Clock-In</th>
                                                    <th class="px-8 py-3 text-center">Clock-Out</th>
                                                    <th class="px-8 py-3 text-center">Duration</th>
                                                    <th class="px-8 py-3">Mark</th>
                                                    <th class="px-8 py-3 text-right">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-50">
                                                @foreach($session->attendanceLogs as $log)
                                                <tr class="hover:bg-slate-50/30 transition-colors">
                                                    <td class="px-8 py-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-[10px]">
                                                                {{ substr($log->student->full_name, 0, 1) }}
                                                            </div>
                                                            <span class="text-sm font-bold text-[#0F172A]">{{ $log->student->full_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-8 py-3 text-xs font-mono text-slate-400">{{ $log->student->reg_number }}</td>
                                                    <td class="px-8 py-3 text-xs text-center font-medium text-slate-600">{{ $log->clock_in->format('h:i A') }}</td>
                                                    <td class="px-8 py-3 text-xs text-center font-medium text-slate-600">{{ $log->clock_out ? $log->clock_out->format('h:i A') : '-' }}</td>
                                                    <td class="px-8 py-3 text-xs text-center text-slate-400">
                                                        @if($log->clock_out)
                                                            {{ $log->clock_in->diffInMinutes($log->clock_out) }}m
                                                        @else
                                                            <span class="text-blue-500 font-bold animate-pulse">Active</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-8 py-3">
                                                        @php 
                                                            $score = $log->attendance_mark;
                                                            if ($score !== null && $score <= 1) $score = $score * 10;
                                                            $score = $score ?? 0;
                                                            
                                                            $barColor = $score >= 7 ? 'bg-emerald-500' : ($score >= 4 ? 'bg-orange-500' : 'bg-red-500');
                                                            $textColor = $score >= 7 ? 'text-emerald-600' : ($score >= 4 ? 'text-orange-600' : 'text-red-600');
                                                        @endphp
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-xs font-bold {{ $textColor }} w-8">{{ round($score) }}/10</span>
                                                            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                                <div class="h-full {{ $barColor }}" style="width: {{ $score * 10 }}%"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-8 py-3 text-right">
                                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $log->clock_out ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }} uppercase">
                                                            {{ $log->clock_out ? 'Signed Out' : 'In Class' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                        @if(!$sessionsFound)
                            <div class="p-6 text-center bg-white rounded-xl border border-dashed border-slate-200">
                                <p class="text-xs text-slate-400 italic">No session records for this department today.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <p class="text-sm text-slate-400 italic">No departments registered for this faculty.</p>
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-12 text-center border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-[#0F172A] font-bold">No attendance records found</h3>
            <p class="text-sm text-slate-400 mt-1">Try adjusting your filters or checking a different date.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .faculty-card.expanded { margin-bottom: 2rem; }
    .rotate-180 { transform: rotate(180deg); }
</style>

<script>
    function toggleSection(id) {
        const content = document.getElementById('content-' + id);
        const arrow = document.getElementById('arrow-' + id);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if(arrow) arrow.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            if(arrow) arrow.classList.remove('rotate-180');
        }
    }

    function toggleAll(expand) {
        const contents = document.querySelectorAll('[id^="content-"]');
        const arrows = document.querySelectorAll('[id^="arrow-"]');
        
        contents.forEach(content => {
            if (expand) content.classList.remove('hidden');
            else content.classList.add('hidden');
        });

        arrows.forEach(arrow => {
            if (expand) arrow.classList.add('rotate-180');
            else arrow.classList.remove('rotate-180');
        });
    }

    function loadDepartments(facultyId) {
        if (!facultyId) {
            document.getElementById('department_id').innerHTML = '<option value="">All Departments</option>';
            return;
        }

        fetch(`/admin/get-departments/${facultyId}`)
            .then(response => response.json())
            .then(data => {
                let html = '<option value="">All Departments</option>';
                data.forEach(dept => {
                    html += `<option value="${dept.id}">${dept.department_name}</option>`;
                });
                document.getElementById('department_id').innerHTML = html;
            });
    }

    // Load initial departments if faculty is selected
    @if(request('faculty_id'))
        loadDepartments({{ request('faculty_id') }});
    @endif
</script>
@endsection
