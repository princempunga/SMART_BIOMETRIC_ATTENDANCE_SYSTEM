@extends('layouts.lecturer')

@section('page_title', 'Active Session')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Active Session Banner -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#2563EB] to-[#1D4ED8] p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-bold uppercase tracking-widest">Week {{ $session->week_number }}</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $session->status }}</span>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold">{{ $session->course->course_name }}</h2>
                    <p class="text-blue-100 mt-1">{{ $session->classroom->room_name }} • Scheduled: {{ \Carbon\Carbon::parse($session->timetable->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->timetable->end_time)->format('H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Session Timer</p>
                    <div id="sessionTimer" class="text-3xl font-mono font-bold">00:00:00</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <!-- OTP Section -->
            <div class="p-8 flex flex-col items-center justify-center text-center">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-4">Verification OTP</p>
                @if($session->isPending())
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl px-8 py-6">
                    <span class="text-4xl font-mono font-black text-[#0F172A] tracking-[0.2em]">{{ $session->otp }}</span>
                </div>
                <form action="{{ route('lecturer.sessions.verify', $session) }}" method="POST" class="mt-6 w-full">
                    @csrf
                    <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-xl shadow-lg transition-all text-sm">
                        Verify & Activate
                    </button>
                    <p class="text-[10px] text-slate-400 mt-2 italic">Verify OTP to begin biometric tracking</p>
                </form>
                @else
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl px-8 py-6">
                    <div class="flex items-center gap-2 text-emerald-600 mb-1 justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="font-bold text-sm uppercase">Verified</span>
                    </div>
                    <span class="text-2xl font-mono font-black text-slate-400 line-through tracking-widest">{{ $session->otp }}</span>
                </div>
                @endif
            </div>

            <!-- Attendance Stats -->
            <div class="p-8 flex flex-col items-center justify-center text-center">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-4">Students Present</p>
                <div class="relative">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100"></circle>
                        <circle id="attendanceCircle" cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="364.4" stroke-dashoffset="364.4" class="text-[#2563EB] transition-all duration-1000"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span id="attendanceCount" class="text-4xl font-black text-[#0F172A]">{{ $session->attendanceLogs()->count() }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Enrolled: 45</span>
                    </div>
                </div>
            </div>

            <!-- Session Actions -->
            <div class="p-8 flex flex-col items-center justify-center space-y-4">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Controls</p>
                <form action="{{ route('lecturer.sessions.complete', $session) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" onclick="return confirm('End this session? No more attendance will be recorded.')" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Complete Session</span>
                    </button>
                </form>
                <a href="{{ route('lecturer.test-environment') }}" target="_blank" class="w-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-3 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.477 2.387a2 2 0 00.547 2.022 2 2 0 002.022.547l2.387-.477a2 2 0 001.414-1.96l-.477-2.387a2 2 0 00-2.022-.547z"></path></svg>
                    <span>Open Test Panel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Live Log -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-[#0F172A]">Real-time Attendance Log</h3>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Live Polling</span>
            </div>
        </div>
        <div id="attendanceLogs" class="p-6 space-y-4 max-h-[400px] overflow-y-auto">
            @forelse($session->attendanceLogs()->with('student')->latest()->get() as $log)
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-full border border-slate-200 flex items-center justify-center text-slate-400 font-bold text-xs">
                        {{ substr($log->student->name, 0, 1) }}
                    </div>
                    <div>
                        <h5 class="font-bold text-sm text-[#0F172A]">{{ $log->student->name }}</h5>
                        <p class="text-[10px] text-slate-500">{{ $log->student->reg_number }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-12">
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">In</p>
                        <p class="font-mono text-sm font-bold text-[#2563EB]">{{ $log->clock_in->format('H:i:s') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">Out</p>
                        <p class="font-mono text-sm font-bold text-slate-400">{{ $log->clock_out ? $log->clock_out->format('H:i:s') : '—' }}</p>
                    </div>
                    <div class="text-right min-w-[60px]">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">Duration</p>
                        <p class="text-xs font-bold text-slate-600">{{ $log->duration ?? 0 }}m</p>
                    </div>
                </div>
            </div>
            @empty
            <div id="noAttendanceMsg" class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <p class="text-slate-400 text-sm italic">Waiting for biometric scans...</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Timer Logic
    let startTime = new Date("{{ $session->session_start }}").getTime();
    setInterval(function() {
        let now = new Date().getTime();
        let diff = now - startTime;
        let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000);
        document.getElementById("sessionTimer").innerHTML = 
            (hours < 10 ? "0" + hours : hours) + ":" + 
            (minutes < 10 ? "0" + minutes : minutes) + ":" + 
            (seconds < 10 ? "0" + seconds : seconds);
    }, 1000);

    // AJAX Polling for Attendance Count
    let enrolledCount = 45;
    let circleTotal = 364.4;

    function updateAttendance() {
        // Update Count
        fetch("{{ route('lecturer.sessions.count', $session) }}")
            .then(response => response.json())
            .then(data => {
                let count = data.count;
                document.getElementById('attendanceCount').innerText = count;
                
                // Update circle
                let offset = circleTotal - (count / enrolledCount * circleTotal);
                document.getElementById('attendanceCircle').style.strokeDashoffset = offset;
            });

        // Update Logs
        fetch("{{ route('lecturer.sessions.logs', $session) }}")
            .then(response => response.json())
            .then(data => {
                const logsContainer = document.getElementById('attendanceLogs');
                if (data.length > 0) {
                    let html = '';
                    data.forEach(log => {
                        html += `
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white rounded-full border border-slate-200 flex items-center justify-center text-slate-400 font-bold text-xs">
                                        ${log.student_name.charAt(0)}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-sm text-[#0F172A]">${log.student_name}</h5>
                                        <p class="text-[10px] text-slate-500">${log.reg_number}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-12">
                                    <div class="text-center">
                                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">In</p>
                                        <p class="font-mono text-sm font-bold text-[#2563EB]">${log.clock_in}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">Out</p>
                                        <p class="font-mono text-sm font-bold ${log.clock_out === '—' ? 'text-slate-300' : 'text-emerald-500'}">${log.clock_out}</p>
                                    </div>
                                    <div class="text-right min-w-[60px]">
                                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">Duration</p>
                                        <p class="text-xs font-bold text-slate-600">${log.duration}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    logsContainer.innerHTML = html;
                } else {
                    logsContainer.innerHTML = `
                        <div id="noAttendanceMsg" class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <p class="text-slate-400 text-sm italic">Waiting for biometric scans...</p>
                        </div>
                    `;
                }
            });
    }

    @if($session->isActive())
    setInterval(updateAttendance, 5000);
    @endif
</script>
@endsection
