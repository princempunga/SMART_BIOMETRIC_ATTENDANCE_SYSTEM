@extends('layouts.dean')
@section('page_title', 'Faculty Students')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-slate-100">
        <div>
            <h3 class="font-bold text-[#0F172A] text-lg">Students Management</h3>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">Faculty Overview — {{ auth()->user()->faculty->faculty_name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-md font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Student</span>
            </button>
            <button onclick="window.print()" class="p-2.5 bg-slate-50 text-slate-400 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all border border-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </button>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 w-full relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input name="search" value="{{ request('search') }}" placeholder="Search by name, reg number or fingerprint ID..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-slate-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none text-sm font-medium transition-all">
            </div>
            <div class="w-full md:w-64">
                <select name="department_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none text-sm font-bold text-slate-600 transition-all">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto bg-[#2563EB] text-white px-8 py-2.5 rounded-lg font-bold text-sm hover:bg-[#1D4ED8] shadow-lg shadow-blue-500/20 transition-all">Filter</button>
            @if(request()->anyFilled(['search', 'department_id']))
            <a href="{{ route('dean.students') }}" class="text-xs font-bold text-red-500 hover:underline uppercase tracking-wider">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Student Info</th>
                        <th class="px-6 py-4">Reg Number</th>
                        <th class="px-6 py-4">Academic Info</th>
                        <th class="px-6 py-4">Biometric ID</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}" class="w-10 h-10 rounded-md object-cover border border-slate-200 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 font-bold text-xs">
                                            {{ substr($item->full_name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-[#0F172A] text-sm group-hover:text-[#2563EB] transition-colors">{{ $item->full_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium italic">ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-[#475569] tracking-tight">{{ $item->reg_number }}</td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] font-bold text-[#475569]">{{ $item->faculty->faculty_name ?? 'N/A' }}</div>
                            <div class="text-[9px] text-slate-400 mt-0.5">{{ $item->department->department_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 {{ $item->fingerprint_id ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-red-50 text-red-600 border-red-100' }} text-[10px] font-bold rounded-md border uppercase">
                                FP: {{ $item->fingerprint_id ?? 'UNSET' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button onclick="openEditModal_students({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-[#2563EB] hover:bg-blue-50 rounded-md transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('dean.students.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete student profile?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No students registered in this faculty yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center {{ $errors->any() && !session('edit_student_id') ? '' : 'hidden' }} p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all my-8">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Register New Student</h3>
                <p class="text-[9px] text-slate-400 mt-1 italic uppercase tracking-[0.2em] font-bold">Faculty: {{ auth()->user()->faculty->faculty_name }}</p>
            </div>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('dean.students.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @if($errors->any() && !session('edit_student_id'))
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-lg flex items-start gap-3">
                    <div class="text-[10px] text-rose-600 space-y-0.5 font-medium">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h4 class="text-[9px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">Personal Information</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name</label>
                        <input type="text" name="full_name" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Registration Number</label>
                        <input type="text" name="reg_number" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                </div>
                <div class="space-y-6">
                    <h4 class="text-[9px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">Academic & Bio</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Department</label>
                        <select name="department_id" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                            <option value="" disabled selected>Choose...</option>
                            @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Fingerprint ID</label>
                        <input type="number" name="fingerprint_id" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Passport Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 border-dashed border-2 text-[10px] font-bold text-slate-400 file:hidden cursor-pointer">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-lg shadow-xl shadow-blue-500/20 transition-all uppercase tracking-widest text-xs">Register Student</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center {{ $errors->any() && session('edit_student_id') ? '' : 'hidden' }} p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all my-8">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Edit Student Profile</h3>
                <p class="text-[9px] text-slate-400 mt-1 italic uppercase tracking-[0.2em] font-bold">Update Enrollment Data</p>
            </div>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm_students" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PATCH')
            @if($errors->any() && session('edit_student_id'))
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-lg flex items-start gap-3">
                    <div class="text-[10px] text-rose-600 space-y-0.5 font-medium">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h4 class="text-[9px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">Student Information</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name</label>
                        <input type="text" name="full_name" id="edit_full_name" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Registration Number</label>
                        <input type="text" name="reg_number" id="edit_reg_number" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-sm">
                    </div>
                </div>
                <div class="space-y-6">
                    <h4 class="text-[9px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">Academic & Bio</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Department</label>
                        <select name="department_id" id="edit_department_id" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-sm">
                            @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Fingerprint ID</label>
                        <input type="number" name="fingerprint_id" id="edit_fingerprint_id" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Update Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-slate-100 border-dashed border-2 text-[10px] font-bold text-slate-400 file:hidden cursor-pointer">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-lg shadow-xl shadow-blue-500/20 transition-all uppercase tracking-widest text-xs">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function openEditModal_students(item) {
        document.getElementById('editForm_students').action = `/dean/students/${item.id}`;
        document.getElementById('edit_full_name').value = item.full_name;
        document.getElementById('edit_reg_number').value = item.reg_number;
        document.getElementById('edit_department_id').value = item.department_id;
        document.getElementById('edit_fingerprint_id').value = item.fingerprint_id;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>

@if(session('success'))
<div id="successToast" class="fixed bottom-6 right-6 z-[150] animate-in slide-in-from-right duration-500">
    <div class="bg-[#10B981] text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-white/20">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white font-bold">✓</div>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">Success</p>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    </div>
</div>
<script>setTimeout(() => { document.getElementById('successToast')?.remove(); }, 5000);</script>
@endif
@endsection
