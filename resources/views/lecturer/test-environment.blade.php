@extends('layouts.lecturer')

@section('page_title', 'SmartAttend - Resilient Persistence Simulation')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Status Bar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div id="status-power" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3 transition-all">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center" id="power-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Power Status</p>
                <p class="font-bold text-[#0F172A]" id="power-text">AC Power Online</p>
            </div>
        </div>
        <div id="status-network" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3 transition-all">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center" id="network-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Connectivity</p>
                <p class="font-bold text-[#0F172A]" id="network-text">API Connected</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Local Buffer</p>
                <p class="font-bold text-[#0F172A]" id="buffer-count">0 Sync Pending</p>
            </div>
        </div>
        <div class="bg-[#0F172A] p-4 rounded-2xl shadow-lg flex items-center gap-3 text-white">
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Uptime</p>
                <p class="font-bold text-sm" id="uptime-text">00:00:00</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Control Panel -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-[#0F172A] mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    <span>Simulation Controls</span>
                </h3>
                
                <div class="space-y-3">
                    <button onclick="togglePower()" class="w-full py-3 px-4 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl font-bold text-xs flex items-center justify-between hover:bg-rose-100 transition-all">
                        <span>Simulate Power Outage</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </button>
                    
                    <button onclick="toggleNetwork()" class="w-full py-3 px-4 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl font-bold text-xs flex items-center justify-between hover:bg-amber-100 transition-all">
                        <span>Kill Internet Connection</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-3.536m0 0l-2.829-2.829m11.314 0a5 5 0 010 7.072"></path></svg>
                    </button>

                    <button onclick="simulateRestart()" class="w-full py-3 px-4 bg-slate-50 text-slate-600 border border-slate-100 rounded-xl font-bold text-xs flex items-center justify-between hover:bg-slate-100 transition-all">
                        <span>Force Device Restart</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Select Target Session</h4>
                    <div class="space-y-2">
                        @foreach($activeSessions as $s)
                        <label class="flex items-center p-3 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-50 gap-3">
                            <input type="radio" name="session_id" value="{{ $s->id }}" {{ $loop->first ? 'checked' : '' }} onchange="updateTarget({{ $s->id }}, '{{ $s->course->course_name }}')">
                            <div>
                                <p class="text-xs font-bold text-[#0F172A]">{{ $s->course->course_code }}</p>
                                <p class="text-[9px] text-slate-500">{{ $s->classroom->room_name }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-[#0F172A] rounded-2xl p-6 text-white shadow-xl">
                <h4 class="font-bold text-xs mb-4 flex items-center justify-between">
                    <span>Virtual ESP32 Console</span>
                    <span class="text-[8px] px-1.5 py-0.5 bg-emerald-500 rounded text-white animate-pulse">Running</span>
                </h4>
                <div id="simLogs" class="space-y-2 max-h-[400px] overflow-y-auto text-[10px] font-mono no-scrollbar">
                    <p class="text-emerald-400/50 italic">> System initialized...</p>
                    <p class="text-emerald-400/50 italic">> Local SPIFFS storage detected: 4MB</p>
                </div>
            </div>
        </div>

        <!-- Scanning Interface -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" id="main-scanner">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                <h3 class="font-bold text-[#0F172A]">Simulate Biometric Scans</h3>
                <div class="flex items-center gap-2 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg" id="current-course">
                    Target: —
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="student-grid">
                    @foreach($students as $student)
                    <div class="p-5 rounded-2xl border border-slate-100 hover:border-blue-500 hover:bg-blue-50 transition-all flex flex-col items-center text-center gap-4 group cursor-pointer" onclick="handleScan({{ $student->fingerprint_id }}, '{{ $student->full_name }}')">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-100 transition-all relative">
                            <svg class="w-8 h-8 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 20m0 0c1.398 0 2.72-.273 3.928-.766l.044.083m-1.404-1.43c1.297-2.507 2.328-5.784 2.328-9.571m0 0c0-4.418-3.582-8-8-8s-8 3.582-8 8m0 0c0 3.787 1.031 7.064 2.328 9.571m11.344-9.571c0 4.418-3.582 8-8 8s-8-3.582-8-8m0 0c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path></svg>
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-[#0F172A] text-white text-[8px] flex items-center justify-center rounded-lg font-bold">{{ $student->fingerprint_id }}</div>
                        </div>
                        <div>
                            <h5 class="font-bold text-sm text-[#0F172A]">{{ $student->full_name }}</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $student->reg_number }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // State Management
    let isPowered = true;
    let isOnline = true;
    let localBuffer = JSON.parse(localStorage.getItem('attendance_buffer') || '[]');
    let activeSessionId = null;
    let uptimeSeconds = 0;

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        const firstSession = document.querySelector('input[name="session_id"]:checked');
        if (firstSession) {
            updateTarget(firstSession.value, firstSession.closest('label').querySelector('p').innerText);
        }
        updateBufferUI();
        startUptime();
        checkPersistentSession();
    });

    function updateTarget(id, name) {
        activeSessionId = id;
        document.getElementById('current-course').innerText = `Target: ${name}`;
        log(`Switched target session to ID: ${id}`, 'info');
    }

    function log(msg, type = 'normal') {
        const simLogs = document.getElementById('simLogs');
        const p = document.createElement('p');
        const time = new Date().toLocaleTimeString();
        
        if (type === 'error') p.className = 'text-rose-400';
        else if (type === 'success') p.className = 'text-emerald-400';
        else if (type === 'info') p.className = 'text-blue-400';
        else p.className = 'text-slate-400';
        
        p.innerHTML = `> [${time}] ${msg}`;
        simLogs.prepend(p);
    }

    function togglePower() {
        isPowered = !isPowered;
        const mainScanner = document.getElementById('main-scanner');
        const powerIcon = document.getElementById('power-icon');
        const powerText = document.getElementById('power-text');

        if (!isPowered) {
            mainScanner.classList.add('opacity-10', 'pointer-events-none');
            powerIcon.className = 'w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center';
            powerText.innerText = 'Power Failure (OFF)';
            log('CRITICAL: Power supply interrupted! Device shut down.', 'error');
        } else {
            mainScanner.classList.remove('opacity-10', 'pointer-events-none');
            powerIcon.className = 'w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center';
            powerText.innerText = 'AC Power Online';
            log('SYSTEM: Power restored. Booting...', 'success');
            simulateRestart(true);
        }
    }

    function toggleNetwork() {
        isOnline = !isOnline;
        const netIcon = document.getElementById('network-icon');
        const netText = document.getElementById('network-text');

        if (!isOnline) {
            netIcon.className = 'w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center';
            netText.innerText = 'Network Offline';
            log('WARNING: Internet connection lost. Entering Offline Buffer Mode.', 'info');
        } else {
            netIcon.className = 'w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center';
            netText.innerText = 'API Connected';
            log('SYSTEM: Internet restored. Synchronizing buffer...', 'success');
            syncBuffer();
        }
    }

    function simulateRestart(isRecovery = false) {
        log('REBOOT: System restarting...', 'info');
        uptimeSeconds = 0;
        
        if (isRecovery) {
            setTimeout(() => {
                log('RECOVERY: Detecting saved session in non-volatile memory...', 'info');
                attemptSessionRestore();
            }, 1000);
        }
    }

    function attemptSessionRestore() {
        const savedSessionId = localStorage.getItem('active_session_id');
        if (!savedSessionId) {
            log('RECOVERY: No saved session found. Ready for new OTP.', 'normal');
            return;
        }

        log(`RECOVERY: Attempting to restore session ${savedSessionId}...`, 'info');
        
        if (!isOnline) {
            log('RECOVERY: Still offline. Using cached session metadata.', 'info');
            return;
        }

        fetch('/api/restore-session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                session_id: savedSessionId,
                device_code: 'ESP32-SA-001' // Demo device code
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                log(`RESTORED: Successfully re-joined ${data.course}! No new OTP needed.`, 'success');
                alert(`Session Restored: ${data.course} in ${data.classroom}`);
            } else {
                log('RESTORED: Saved session is no longer active on server.', 'error');
                localStorage.removeItem('active_session_id');
            }
        });
    }

    function handleScan(fingerprintId, name) {
        if (!isPowered) return;
        
        log(`SCAN: Detected Fingerprint ID: ${fingerprintId} (${name})`, 'normal');
        
        // Save current session to "Non-Volatile Memory" (localStorage)
        localStorage.setItem('active_session_id', activeSessionId);

        if (!isOnline) {
            const entry = {
                fingerprint_id: fingerprintId,
                timestamp: new Date().toISOString(),
                type: 'clock_in',
                name: name
            };
            localBuffer.push(entry);
            localStorage.setItem('attendance_buffer', JSON.stringify(localBuffer));
            updateBufferUI();
            log(`BUFFER: Data saved to local SPIFFS. (Pending sync)`, 'info');
            return;
        }

        // Live Scan
        fetch("{{ route('lecturer.simulate-scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                session_id: activeSessionId,
                student_id: fingerprintId // Simplification for simulation
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) log(`SERVER: ${data.success}`, 'success');
            else log(`SERVER ERROR: ${data.error}`, 'error');
        });
    }

    function syncBuffer() {
        if (localBuffer.length === 0) return;
        
        log(`SYNC: Pushing ${localBuffer.length} logs to backend...`, 'info');
        
        fetch('/api/sync-attendance', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                session_id: activeSessionId,
                logs: localBuffer
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                log(`SYNC COMPLETED: ${data.message}`, 'success');
                localBuffer = [];
                localStorage.setItem('attendance_buffer', '[]');
                updateBufferUI();
            }
        });
    }

    function updateBufferUI() {
        document.getElementById('buffer-count').innerText = `${localBuffer.length} Sync Pending`;
    }

    function startUptime() {
        setInterval(() => {
            if (isPowered) {
                uptimeSeconds++;
                const h = Math.floor(uptimeSeconds / 3600).toString().padStart(2, '0');
                const m = Math.floor((uptimeSeconds % 3600) / 60).toString().padStart(2, '0');
                const s = (uptimeSeconds % 60).toString().padStart(2, '0');
                document.getElementById('uptime-text').innerText = `${h}:${m}:${s}`;
            }
        }, 1000);
    }

    function checkPersistentSession() {
        const saved = localStorage.getItem('active_session_id');
        if (saved) {
            log('BOOT: Found persistent session data from previous power cycle.', 'info');
        }
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
