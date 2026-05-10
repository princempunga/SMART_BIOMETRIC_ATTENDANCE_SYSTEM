@extends('layouts.lecturer')

@section('page_title', 'Attendance Simulation Environment')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 flex items-center gap-4 text-amber-700">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <p class="font-bold">Testing Environment Active</p>
            <p class="text-xs">Use this panel to simulate biometric fingerprint scans for active sessions. This environment is restricted to non-production only.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Student Directory -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-[#0F172A]">Student Directory (Simulated Biometrics)</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($students as $student)
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-blue-100 group-hover:text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-sm text-[#0F172A]">{{ $student->full_name }}</h5>
                                <p class="text-[10px] text-slate-500">{{ $student->reg_number }}</p>
                            </div>
                        </div>
                        <button onclick="scanStudent({{ $student->id }}, '{{ $student->full_name }}')" class="px-4 py-2 bg-[#2563EB] text-white text-[10px] font-bold rounded-lg uppercase tracking-wider opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                            Scan
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Simulation Control -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-[#0F172A] mb-4">Current Session Target</h3>
                <div class="space-y-4">
                    @forelse($activeSessions as $active)
                    <label class="block p-4 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-50 relative overflow-hidden group">
                        <input type="radio" name="target_session" value="{{ $active->id }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                        <div class="peer-checked:border-blue-500 absolute inset-0 border-2 border-transparent rounded-xl pointer-events-none transition-all"></div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="font-bold text-sm text-[#0F172A]">{{ $active->course->course_code }}</h6>
                                <p class="text-[10px] text-slate-500">{{ $active->classroom->room_name }}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-blue-50 text-[#2563EB] text-[8px] font-bold rounded uppercase tracking-widest">Active</span>
                        </div>
                    </label>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-slate-400 text-[10px] italic">No active sessions to target.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-[#0F172A] rounded-2xl p-6 text-white shadow-xl">
                <h4 class="font-bold text-sm mb-4">Simulation Logs</h4>
                <div id="simLogs" class="space-y-3 max-h-[300px] overflow-y-auto text-[10px] font-mono">
                    <p class="text-slate-500 italic">Waiting for simulation events...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function scanStudent(studentId, name) {
        const sessionRadio = document.querySelector('input[name="target_session"]:checked');
        if (!sessionRadio) {
            alert('Please select an active session target first.');
            return;
        }

        const sessionId = sessionRadio.value;
        const logContainer = document.getElementById('simLogs');

        if (logContainer.querySelector('p.italic')) {
            logContainer.innerHTML = '';
        }

        fetch("{{ route('lecturer.simulate-scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                session_id: sessionId,
                student_id: studentId
            })
        })
        .then(response => response.json())
        .then(data => {
            const entry = document.createElement('div');
            entry.className = 'p-2 rounded bg-white/5 border border-white/10 flex justify-between items-center';
            
            if (data.success) {
                entry.innerHTML = `
                    <span class="text-green-400">[SUCCESS]</span>
                    <span class="flex-1 px-2">${name} scanned.</span>
                    <span class="text-slate-500">${new Date().toLocaleTimeString()}</span>
                `;
            } else {
                entry.innerHTML = `
                    <span class="text-red-400">[FAILED]</span>
                    <span class="flex-1 px-2">${data.error}</span>
                    <span class="text-slate-500">${new Date().toLocaleTimeString()}</span>
                `;
            }
            logContainer.prepend(entry);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to connect to simulation server.');
        });
    }
</script>
@endsection
