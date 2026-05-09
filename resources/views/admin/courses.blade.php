@extends('layouts.admin')

@section('page_title', 'Courses')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-[#0F172A]">Course Management</h3>
        <button onclick="document.getElementById('addCourseModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Course</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-bold text-[#475569] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Course Name</th>
                        <th class="px-6 py-4">Course Code</th>
                        <th class="px-6 py-4">Lecturer</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-[#0F172A] text-sm">{{ $course->course_name }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-[#2563EB] uppercase tracking-wider">{{ $course->course_code }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569] font-medium">{{ $course->lecturer->name }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="openEditCourseModal({{ json_encode($course) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
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
                        <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic text-sm font-medium">No courses added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div id="addCourseModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Add New Course</h3>
                <p class="text-xs text-slate-400 mt-1 italic">Define a new subject for the academic term</p>
            </div>
            <button onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.courses.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Course Name</label>
                <input type="text" name="course_name" required placeholder="e.g. Data Structures & Algorithms" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Course Code</label>
                <input type="text" name="course_code" required placeholder="e.g. CSC201" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Assigned Lecturer</label>
                <select name="lecturer_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    <option value="" disabled selected>Select a lecturer</option>
                    @foreach(\App\Models\User::where('role', 'lecturer')->get() as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98]">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Course Modal -->
<div id="editCourseModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-[#0F172A]">Edit Course</h3>
                <p class="text-xs text-slate-400 mt-1 italic">Update subject information</p>
            </div>
            <button onclick="document.getElementById('editCourseModal').classList.add('hidden')" class="text-slate-400 hover:text-[#0F172A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editCourseForm" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Course Name</label>
                <input type="text" name="course_name" id="edit_course_name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Course Code</label>
                <input type="text" name="course_code" id="edit_course_code" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Assigned Lecturer</label>
                <select name="lecturer_id" id="edit_lecturer_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
                    @foreach(\App\Models\User::where('role', 'lecturer')->get() as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
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
    function openEditCourseModal(course) {
        const modal = document.getElementById('editCourseModal');
        const form = document.getElementById('editCourseForm');
        
        form.action = `/admin/courses/${course.id}`;
        document.getElementById('edit_course_name').value = course.course_name;
        document.getElementById('edit_course_code').value = course.course_code;
        document.getElementById('edit_lecturer_id').value = course.lecturer_id;
        
        modal.classList.remove('hidden');
    }
</script>
@endsection
