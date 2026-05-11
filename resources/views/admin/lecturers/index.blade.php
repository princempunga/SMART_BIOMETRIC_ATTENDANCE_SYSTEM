@extends('layouts.admin')

@section('page_title', 'Lecturers')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-slate-100">
        <h3 class="font-bold text-[#0F172A]">Lecturers Management</h3>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-md font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Lecturer</span>
        </button>
    </div>

    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] font-bold text-[#475569] uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Lecturer Information</th>
                        <th class="px-6 py-4">Academic Placement</th>
                        <th class="px-6 py-4">Assigned Course Units</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturers as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($item->profile_photo)
                                    <img src="{{ asset('storage/' . $item->profile_photo) }}" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                         class="w-10 h-10 rounded-md object-cover border border-slate-200">
                                    <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 font-bold text-xs" style="display:none">
                                        {{ substr($item->name, 0, 2) }}
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 font-bold text-xs">
                                        {{ substr($item->name, 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-[#0F172A] text-sm">{{ $item->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-[#475569]">{{ $item->faculty->faculty_name ?? 'N/A' }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $item->department->department_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($item->courseUnits as $unit)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-bold rounded-md border border-blue-100 uppercase">
                                        {{ $unit->course_code }}
                                    </span>
                                @empty
                                    <span class="text-[10px] text-slate-300 italic">None assigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div class="font-medium text-slate-600">{{ $item->email }}</div>
                            <div class="text-[10px] text-blue-500 font-bold mt-0.5">{{ $item->phone ?? 'No phone' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button onclick="openEditModal({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.lecturers.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No lecturers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-2xl overflow-hidden transform transition-all my-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Register New Lecturer</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.lecturers.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="text-[10px] font-bold text-rose-700 uppercase tracking-widest">Registration Errors</h4>
                        <ul class="mt-1 list-disc list-inside text-[10px] text-rose-600 space-y-0.5 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Account Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Faculty</label>
                        <select name="faculty_id" id="add_faculty_id" onchange="loadCourseUnits(this.value, 'add_course_units')" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                            <option value="" disabled selected>Choose Faculty...</option>
                            @foreach($faculties as $fac)
                            <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Department</label>
                        <select name="department_id" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                            <option value="" disabled selected>Choose Department...</option>
                            @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Assign Course Units</label>
                        <div id="add_course_units_container" class="border border-slate-100 rounded-md bg-slate-50 p-3 max-h-[150px] overflow-y-auto space-y-2">
                            <p class="text-[10px] text-slate-400 italic">Select a faculty first...</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full px-4 py-2 text-[10px] font-bold text-slate-400 bg-slate-50 border border-slate-100 rounded-md">
                    </div>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-md shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs">
                    Register Lecturer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-2xl overflow-hidden transform transition-all my-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Edit Lecturer</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</label>
                        <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Faculty</label>
                        <select name="faculty_id" id="edit_faculty_id" onchange="loadCourseUnits(this.value, 'edit_course_units')" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                            @foreach($faculties as $fac)
                            <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Department</label>
                        <select name="department_id" id="edit_department_id" required class="w-full px-4 py-2.5 rounded-md bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                            @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Assign Course Units</label>
                        <div id="edit_course_units_container" class="border border-slate-100 rounded-md bg-slate-50 p-3 max-h-[150px] overflow-y-auto space-y-2">
                            <!-- Course units will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-md shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs">
                    Update Lecturer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadCourseUnits(facultyId, targetId, selectedIds = []) {
        const container = document.getElementById(targetId + '_container');
        container.innerHTML = '<div class="text-[10px] text-blue-500 font-bold animate-pulse uppercase tracking-widest">Loading units...</div>';
        
        try {
            const response = await fetch(`/admin/api/faculties/${facultyId}/course-units`);
            const units = await response.json();
            
            if (units.length === 0) {
                container.innerHTML = '<p class="text-[10px] text-slate-400 italic">No course units found for this faculty.</p>';
                return;
            }

            container.innerHTML = units.map(unit => `
                <label class="flex items-center gap-3 p-2 bg-white rounded border border-slate-100 hover:border-blue-200 cursor-pointer transition-all">
                    <input type="checkbox" name="course_unit_ids[]" value="${unit.id}" 
                        ${selectedIds.includes(unit.id) ? 'checked' : ''}
                        class="w-4 h-4 rounded border-slate-200 text-blue-600 focus:ring-blue-500">
                    <div class="flex flex-col">
                        <span class="text-[11px] font-bold text-[#0F172A] uppercase">${unit.course_code}</span>
                        <span class="text-[10px] text-slate-400 font-medium">${unit.course_name}</span>
                    </div>
                </label>
            `).join('');
        } catch (error) {
            container.innerHTML = '<p class="text-[10px] text-rose-500 font-bold italic uppercase tracking-widest">Failed to load units.</p>';
        }
    }

    function openEditModal(item) {
        document.getElementById('editForm').action = `/admin/lecturers/${item.id}`;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_email').value = item.email;
        document.getElementById('edit_phone').value = item.phone || '';
        document.getElementById('edit_faculty_id').value = item.faculty_id;
        document.getElementById('edit_department_id').value = item.department_id;
        
        const selectedIds = item.course_units ? item.course_units.map(u => u.id) : [];
        loadCourseUnits(item.faculty_id, 'edit_course_units', selectedIds);
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Auto-open modal if validation errors exist
    @if($errors->any())
        document.getElementById('addModal').classList.remove('hidden');
    @endif
</script>
@endsection