@extends('layouts.admin')

@section('page_title', 'Departments')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-[#0F172A]">Departments Management</h3>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Departments</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Department Name</th>
                        <th class="px-6 py-4">Faculty ID</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($departments as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-[#475569]">{{ $item->id }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->department_name }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->faculty->faculty_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="openEditModal_departments({{ json_encode($item) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.departments.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No departments found.</td>
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
                <h3 class="text-xl font-bold text-[#0F172A]">Add New Departments</h3>
            </div>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.departments.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department Name</label>
                <input type="text" name="department_name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Faculty ID</label>
                <select name="faculty_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="">Select Faculty</option>
                    @foreach(\App\Models\Faculty::all() as $fac)
                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98]">
                    Save Departments
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
                <h3 class="text-xl font-bold text-[#0F172A]">Edit Departments</h3>
            </div>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm_departments" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department Name</label>
                <input type="text" name="department_name" id="edit_department_name_departments" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Faculty ID</label>
                <select name="faculty_id" id="edit_faculty_id_departments" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="">Select Faculty</option>
                    @foreach(\App\Models\Faculty::all() as $fac)
                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
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
    function openEditModal_departments(item) {
        document.getElementById('editForm_departments').action = `/admin/departments/${item.id}`;
        document.getElementById('edit_department_name_departments').value = item.department_name;
        document.getElementById('edit_faculty_id_departments').value = item.faculty_id;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection