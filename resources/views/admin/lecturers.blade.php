@extends('layouts.admin')

@section('page_title', 'Lecturer Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h3 class="font-bold text-[#0F172A] text-lg">Faculty Staff Management</h3>
            <p class="text-xs text-slate-400 mt-1">Manage university lecturers and academic assignments</p>
        </div>
        <button onclick="document.getElementById('addLecturerModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Lecturer</span>
        </button>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Lecturer</th>
                        <th class="px-6 py-4">Academic Placement</th>
                        <th class="px-6 py-4">Assigned Subjects</th>
                        <th class="px-6 py-4">Contact Details</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturers as $lecturer)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($lecturer->profile_photo)
                                    <img src="{{ asset('storage/' . $lecturer->profile_photo) }}" alt="" class="w-12 h-12 rounded-xl object-cover shadow-sm border-2 border-white">
                                @else
                                    <div class="w-12 h-12 bg-[#2563EB]/10 rounded-xl flex items-center justify-center text-[#2563EB] font-bold text-sm border-2 border-white shadow-sm uppercase">
                                        {{ substr($lecturer->name, 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-[#0F172A] text-sm">{{ $lecturer->name }}</div>
                                    <div class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-wider">{{ $lecturer->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-[#475569]">{{ $lecturer->faculty->faculty_name ?? 'No Faculty' }}</div>
                            <div class="text-[11px] text-[#94A3B8]">{{ $lecturer->department->department_name ?? 'No Department' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($lecturer->courses->isEmpty())
                                <span class="text-[11px] text-slate-400 italic">No subjects assigned</span>
                            @else
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @foreach($lecturer->courses as $course)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#2563EB]/10 text-[#2563EB] text-[10px] font-bold rounded-lg uppercase tracking-wide whitespace-nowrap" title="{{ $course->course_name }}">
                                        <svg class="w-2.5 h-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                                        {{ $course->course_code }}
                                    </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-[#475569]">{{ $lecturer->email }}</div>
                            <div class="text-[11px] text-[#2563EB] font-bold">{{ $lecturer->phone ?? 'No Phone' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="openEditLecturerModal({{ json_encode($lecturer) }})" class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.lecturers.destroy', $lecturer->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this lecturer account permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No lecturers registered in the academic system.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Lecturer Modal -->
<div id="addLecturerModal" class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden transform transition-all my-8">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-2xl font-bold text-[#0F172A]">Add New Lecturer</h3>
                <p class="text-xs text-slate-400 mt-1 italic uppercase tracking-widest font-bold">Academic Registration Portal</p>
            </div>
            <button onclick="document.getElementById('addLecturerModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.lecturers.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">General Information</h4>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm" placeholder="+256 7xx xxx xxx">
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">Academic Placement</h4>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Select Faculty</label>
                        <select name="faculty_id" onchange="loadDepartments(this.value, 'add_dept')" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                            <option value="" disabled selected>Choose a Faculty...</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->faculty_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Select Department</label>
                        <select name="department_id" id="add_dept" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                            <option value="" disabled selected>Select Faculty First...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 border-dashed border-2 text-xs font-bold text-slate-400 file:hidden cursor-pointer">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Account Password</label>
                    <input type="password" name="password" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-5 rounded-[1.5rem] shadow-xl shadow-blue-500/30 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Confirm Registration</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Lecturer Modal -->
<div id="editLecturerModal" class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden transform transition-all my-8">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-2xl font-bold text-[#0F172A]">Edit Lecturer Profile</h3>
                <p class="text-xs text-slate-400 mt-1 italic uppercase tracking-widest font-bold">Update Faculty Record</p>
            </div>
            <button onclick="document.getElementById('editLecturerModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editLecturerForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">General Information</h4>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
                        <input type="text" name="phone" id="edit_phone" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold text-[#2563EB] uppercase tracking-[0.2em]">Academic Placement</h4>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Select Faculty</label>
                        <select name="faculty_id" id="edit_faculty" onchange="loadDepartments(this.value, 'edit_dept')" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->faculty_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Select Department</label>
                        <select name="department_id" id="edit_dept" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                            <!-- Populated via JS -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Update Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 border-dashed border-2 text-xs font-bold text-slate-400 file:hidden cursor-pointer">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Reset Password (Optional)</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-5 rounded-[1.5rem] shadow-xl shadow-blue-500/30 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Profile Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadDepartments(facultyId, targetId, selectedId = null) {
        const select = document.getElementById(targetId);
        select.innerHTML = '<option value="" disabled selected>Loading...</option>';
        
        try {
            const response = await fetch(`/admin/departments/${facultyId}`);
            const departments = await response.json();
            
            select.innerHTML = '<option value="" disabled selected>Select Department...</option>';
            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.id;
                option.textContent = dept.department_name;
                if(selectedId && dept.id == selectedId) option.selected = true;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading departments:', error);
            select.innerHTML = '<option value="" disabled>Error loading data</option>';
        }
    }

    function openEditLecturerModal(lecturer) {
        const modal = document.getElementById('editLecturerModal');
        const form = document.getElementById('editLecturerForm');
        
        form.action = `/admin/lecturers/${lecturer.id}`;
        document.getElementById('edit_name').value = lecturer.name;
        document.getElementById('edit_email').value = lecturer.email;
        document.getElementById('edit_phone').value = lecturer.phone || '';
        document.getElementById('edit_faculty').value = lecturer.faculty_id;
        
        // Trigger department load and pre-select
        loadDepartments(lecturer.faculty_id, 'edit_dept', lecturer.department_id);
        
        modal.classList.remove('hidden');
    }
</script>
@endsection
