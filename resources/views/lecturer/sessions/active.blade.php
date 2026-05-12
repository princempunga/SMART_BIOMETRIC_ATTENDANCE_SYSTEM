@extends('layouts.lecturer')

@section('page_title', 'Active Session')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Active Session Banner -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#2563EB] to-[#1D4ED8] p-8 text-white relative">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-blue-500/20 rounded-lg text-[10px] font-bold uppercase tracking-widest text-blue-300">Week {{ $session->week_number }}</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 {{ $session->isActive() ? 'bg-green-400' : 'bg-orange-400' }} rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-300">{{ $session->status }}</span>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold">{{ $session->course->course_name }}</h2>
                    <p class="text-slate-100 mt-1 flex items-center gap-2 opacity-80">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $session->classroom->room_name }}
                    </p>
                </div>
                
                <div class="flex items-center gap-8 bg-black/20 p-6 rounded-2xl backdrop-blur-sm border border-white/5">
                    <div class="text-center">
                        <p class="text-slate-300 text-[10px] font-bold uppercase tracking-widest mb-1">Session Timer</p>
                        <div id="sessionTimer" class="text-3xl font-mono font-bold text-white">00:00:00</div>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-slate-300 text-[10px] font-bold uppercase tracking-widest mb-1">OTP Status</p>
                        @if($session->isActive())
                            <span class="text-emerald-400 font-bold text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Verified
                            </span>
                        @else
                            <span class="text-orange-400 font-bold text-sm animate-pulse">Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Infrastructure Resilience Indicators -->
            <div class="mt-8 pt-6 border-t border-white/10 flex flex-wrap gap-6 items-center">
                <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest bg-white/10 px-3 py-1.5 rounded-full border border-white/5">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></div>
                    <span>Power: AC Online</span>
                </div>
                <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest bg-white/10 px-3 py-1.5 rounded-full border border-white/5">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full"></div>
                    <span>Net: Device Connected</span>
                </div>
                <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest bg-white/10 px-3 py-1.5 rounded-full border border-white/5">
                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full"></div>
                    <span>Recovery: Auto-Sync Enabled</span>
                </div>
                @if(request()->has('restored'))
                <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest bg-emerald-500/20 px-3 py-1.5 rounded-full border border-emerald-400/50 text-emerald-100 animate-bounce">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Session Restored Successfully</span>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            <!-- OTP Verification -->
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-[#0F172A] text-sm uppercase tracking-wider">Security Access</h3>
                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
                
                @if($session->isPending())
                <div class="text-center space-y-6">
                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl py-8 relative group overflow-hidden">
                        <div class="absolute inset-0 bg-blue-600/5 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        <span class="text-5xl font-mono font-black text-[#0F172A] tracking-[0.2em] relative z-10">{{ $session->otp }}</span>
                    </div>
                    <form action="{{ route('lecturer.sessions.verify', $session) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-3">
                            <span>Verify & Activate Session</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </form>
                    <p class="text-[10px] text-slate-400 italic">Verifying OTP activates the biometric scanner in the classroom.</p>
                </div>
                @else
                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h4 class="font-bold text-emerald-800 mb-1">Session Active</h4>
                    <p class="text-xs text-emerald-600/70">Biometric scanner is now receiving data for students.</p>
                </div>
                @endif
            </div>

            <!-- Real-time Stats -->
            <div class="p-8">
                <h3 class="font-bold text-[#0F172A] text-sm uppercase tracking-wider mb-6">Attendance Insight</h3>
                <div class="flex items-center gap-8">
                    <div class="relative flex-shrink-0">
                        <svg class="w-32 h-32 transform -rotate-90">
                            <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100"></circle>
                            <circle id="attendanceCircle" cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="364.4" stroke-dashoffset="364.4" class="text-[#2563EB] transition-all duration-1000"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span id="attendanceCount" class="text-4xl font-black text-[#0F172A]">{{ $session->attendanceLogs()->count() }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Students</span>
                        </div>
                    </div>
                    <div class="space-y-4 flex-1">
                        <div>
                            <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest mb-1">
                                <span class="text-slate-400">Rate</span>
                                <span class="text-blue-600" id="attendanceRateText">0%</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full transition-all duration-1000" id="attendanceRateBar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">In Class</p>
                                <p class="text-sm font-bold text-[#0F172A]" id="inClassCount">0</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Completed</p>
                                <p class="text-sm font-bold text-[#0F172A]" id="completedCount">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session Controls -->
            <div class="p-8 flex flex-col items-center justify-center space-y-4">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Controls</p>
                <form action="{{ route('lecturer.sessions.complete', $session) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" onclick="return confirm('End this session? No more attendance will be recorded.')" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>End Session Now</span>
                    </button>
                </form>
                <a href="{{ route('lecturer.test-environment') }}?target_session={{ $session->id }}" target="_blank" class="w-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-3 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.477 2.387a2 2 0 00.547 2.022 2 2 0 002.022.547l2.387-.477a2 2 0 001.414-1.96l-.477-2.387a2 2 0 00-2.022-.547z"></path></svg>
                    <span>Simulator Panel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Live Log -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-[#0F172A] flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                </span>
                <span>Real-time Academic Stream</span>
            </h3>
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">Auto-updating every 5s</span>
            </div>
        </div>
        
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Student Details</th>
                        <th class="px-8 py-4">Time Log (In/Out)</th>
                        <th class="px-8 py-4 text-center">Current Duration</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Est. Credit</th>
                    </tr>
                </thead>
                <tbody id="attendanceLogs">
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-slate-400 text-sm italic font-medium">Initializing academic stream...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Timer Logic
    const startTimeStr = "{{ $session->session_start->toIso8601String() }}";
    const startTime = new Date(startTimeStr).getTime();
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

    // Global Constants
    const circleTotal = 364.4;
    const enrolledCount = {{ \App\Models\Student::count() > 0 ? \App\Models\Student::count() : 45 }};

    function updateAttendance() {
        fetch("{{ route('lecturer.sessions.live-data', $session) }}")
            .then(response => response.json())
            .then(data => {
                // Update Counters
                document.getElementById('attendanceCount').innerText = data.scanned;
                document.getElementById('inClassCount').innerText = data.in_class;
                document.getElementById('completedCount').innerText = data.completed;
                document.getElementById('attendanceRateText').innerText = data.rate + '%';
                document.getElementById('attendanceRateBar').style.width = data.rate + '%';
                
                // Update Circle
                let offset = circleTotal - (data.scanned / enrolledCount * circleTotal);
                document.getElementById('attendanceCircle').style.strokeDashoffset = offset;

                // Update Logs
                const logsContainer = document.getElementById('attendanceLogs');
                if (data.logs.length > 0) {
                    let html = '';
                    data.logs.forEach(log => {
                        const statusClass = log.status === 'In Class' ? 'bg-blue-50 text-blue-600 animate-pulse' : 'bg-emerald-50 text-emerald-600';
                        const creditColor = log.credit >= 1.0 ? 'text-emerald-600' : (log.credit >= 0.5 ? 'text-orange-500' : 'text-slate-600');
                        
                        html += `
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[#0F172A] font-bold text-xs group-hover:bg-white transition-colors">
                                            ${log.student_name.charAt(0)}
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-[#0F172A]">${log.student_name}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">${log.reg_number}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-4 font-mono text-xs">
                                        <div class="text-blue-600 font-bold">${log.clock_in}</div>
                                        <div class="text-slate-300">→</div>
                                        <div class="${log.clock_out === '—' ? 'text-slate-300' : 'text-[#0F172A] font-bold'}">${log.clock_out}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="text-sm font-bold text-slate-600">${log.duration}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 ${statusClass} rounded-lg text-[9px] font-bold uppercase tracking-widest border border-current">
                                        ${log.status}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-sm font-black ${creditColor}">${log.credit.toFixed(1)}</span>
                                </td>
                            </tr>
                        `;
                    });
                    logsContainer.innerHTML = html;
                } else {
                    logsContainer.innerHTML = `
                        <tr>
                            <td colspan="5" class="py-24 text-center">
                                <p class="text-slate-400 text-sm italic font-medium">No attendance records detected for this session yet.</p>
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => console.error('Error fetching live data:', error));
    }

    @if($session->isActive())
    updateAttendance();
    setInterval(updateAttendance, 5000);
    @endif
</script>
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
