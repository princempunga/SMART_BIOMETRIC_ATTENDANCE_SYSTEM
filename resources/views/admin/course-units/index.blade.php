@extends('layouts.admin')

@section('page_title', 'Course Units')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-slate-100">
        <h3 class="font-bold text-[#0F172A]">Course Units Management</h3>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-md font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Course Unit</span>
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Course Info</th>
                        <th class="px-6 py-4">Faculty</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courseUnits as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 font-bold text-xs">
                                    {{ substr($item->course_code, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-[#0F172A] text-sm">{{ $item->course_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium italic uppercase tracking-widest">{{ $item->course_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md border border-slate-200 uppercase">
                                {{ $item->faculty->faculty_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">
                            {{ $item->department->department_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400">
                            {{ $item->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button onclick="openEditModal({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.course-units.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No course units registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Add Course Unit</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.course-units.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Course Code</label>
                <input type="text" name="course_code" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" placeholder="e.g. CSC220">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Course Name</label>
                <input type="text" name="course_name" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" placeholder="e.g. Database Systems">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Faculty</label>
                <select name="faculty_id" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    <option value="" disabled selected>Choose Faculty...</option>
                    @foreach($faculties as $fac)
                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Department (Optional)</label>
                <select name="department_id" class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    <option value="">None</option>
                    @foreach($departments as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-md shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs">
                    Create Course Unit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Edit Course Unit</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Course Code</label>
                <input type="text" name="course_code" id="edit_course_code" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Course Name</label>
                <input type="text" name="course_name" id="edit_course_name" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Faculty</label>
                <select name="faculty_id" id="edit_faculty_id" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    @foreach($faculties as $fac)
                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Department (Optional)</label>
                <select name="department_id" id="edit_department_id" class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    <option value="">None</option>
                    @foreach($departments as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-md shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(item) {
        document.getElementById('editForm').action = `/admin/course-units/${item.id}`;
        document.getElementById('edit_course_code').value = item.course_code;
        document.getElementById('edit_course_name').value = item.course_name;
        document.getElementById('edit_faculty_id').value = item.faculty_id;
        document.getElementById('edit_department_id').value = item.department_id || '';
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
