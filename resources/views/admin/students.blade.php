@extends('layouts.admin')

@section('page_title', 'Students')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-[#0F172A]">Student Management</h3>
        <button onclick="document.getElementById('addStudentModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Register Student</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Reg Number</th>
                        <th class="px-6 py-4">Faculty</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs border-2 border-white shadow-sm">
                                    {{ collect(explode(' ', $student->name))->map(fn($n) => $n[0])->take(2)->implode('') }}
                                </div>
                                <div class="font-bold text-[#0F172A] text-sm">{{ $student->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-[#475569]">{{ $student->reg_number }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $student->faculty }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $student->department }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="openEditStudentModal({{ json_encode($student) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No students registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Register New Student</h3>
                <p class="text-xs text-slate-400 mt-1 italic">Enter information as per official records</p>
            </div>
            <button onclick="document.getElementById('addStudentModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.students.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Registration Number</label>
                <input type="text" name="reg_number" required placeholder="e.g. CS/2024/001" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Faculty</label>
                    <input type="text" name="faculty" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                    <input type="text" name="department" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98]">
                    Complete Registration
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Edit Student</h3>
                <p class="text-xs text-slate-400 mt-1 italic">Update student record</p>
            </div>
            <button onclick="document.getElementById('editStudentModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editStudentForm" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Registration Number</label>
                <input type="text" name="reg_number" id="edit_reg_number" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Faculty</label>
                    <input type="text" name="faculty" id="edit_faculty" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                    <input type="text" name="department" id="edit_department" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditStudentModal(student) {
        const modal = document.getElementById('editStudentModal');
        const form = document.getElementById('editStudentForm');
        
        form.action = `/admin/students/${student.id}`;
        document.getElementById('edit_name').value = student.name;
        document.getElementById('edit_reg_number').value = student.reg_number;
        document.getElementById('edit_faculty').value = student.faculty;
        document.getElementById('edit_department').value = student.department;
        
        modal.classList.remove('hidden');
    }
</script>
@endsection
