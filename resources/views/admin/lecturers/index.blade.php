@extends('layouts.admin')

@section('page_title', 'Lecturers')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-[#0F172A]">Lecturers Management</h3>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Lecturers</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Photo</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Password (leave blank to keep)</th>
                        <th class="px-6 py-4">Faculty</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturers as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-[#475569]">{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            @if($item->profile_photo)
                                <img src="{{ asset('storage/' . $item->profile_photo) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->email }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->password }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->faculty->faculty_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->department->department_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->subject }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->phone }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="openEditModal_lecturers({{ json_encode($item) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.lecturers.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No lecturers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Add New Lecturers</h3>
            </div>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.lecturers.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
                        <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/*" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email</label>
                <input type="text" name="email" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Password (leave blank to keep)</label>
                <input type="text" name="password"  class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Faculty</label>
                <select name="faculty_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="">Select Faculty</option>
                    @foreach(\App\Models\Faculty::all() as $fac)
                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                <select name="department_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="">Select Department</option>
                    @foreach(\App\Models\Department::all() as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                    @endforeach
                </select>
            </div>
                        <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subject Specialization</label>
                <input type="text" name="subject" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</label>
                <input type="text" name="phone" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98]">
                    Save Lecturers
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Edit Lecturers</h3>
            </div>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm_lecturers" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PATCH')
                        <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/*" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Name</label>
                <input type="text" name="name" id="edit_name_lecturers" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email</label>
                <input type="text" name="email" id="edit_email_lecturers" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Password (leave blank to keep)</label>
                <input type="text" name="password" id="edit_password_lecturers"  class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Faculty</label>
                <select name="faculty_id" id="edit_faculty_id_lecturers" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="">Select Faculty</option>
                    @foreach(\App\Models\Faculty::all() as $fac)
                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                <select name="department_id" id="edit_department_id_lecturers" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="">Select Department</option>
                    @foreach(\App\Models\Department::all() as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                    @endforeach
                </select>
            </div>
                        <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subject Specialization</label>
                <input type="text" name="subject" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subject Specialization</label>
                <input type="text" name="subject" id="edit_subject_lecturers" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</label>
                <input type="text" name="phone" id="edit_phone_lecturers" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
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
    function openEditModal_lecturers(item) {
        document.getElementById('editForm_lecturers').action = `/admin/lecturers/${item.id}`;
        if(document.getElementById('edit_name_lecturers')) document.getElementById('edit_name_lecturers').value = item.name;
        if(document.getElementById('edit_email_lecturers')) document.getElementById('edit_email_lecturers').value = item.email;
        if(document.getElementById('edit_faculty_id_lecturers')) document.getElementById('edit_faculty_id_lecturers').value = item.faculty_id;
        if(document.getElementById('edit_department_id_lecturers')) document.getElementById('edit_department_id_lecturers').value = item.department_id;
        if(document.getElementById('edit_phone_lecturers')) document.getElementById('edit_phone_lecturers').value = item.phone;
        if(document.getElementById('edit_subject_lecturers')) document.getElementById('edit_subject_lecturers').value = item.subject;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection