@extends('layouts.admin')
@section('page_title', 'Dean Management')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div>
            <h3 class="font-bold text-[#0F172A] text-lg">Dean Accounts</h3>
            <p class="text-xs text-slate-400 mt-1">Manage faculty deans — each dean can only view their own faculty data.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')"
            class="bg-[#0F172A] hover:bg-[#1E293B] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-slate-900/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Dean
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Dean Information</th>
                    <th class="px-6 py-4">Faculty Assigned</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($deans as $dean)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm border border-purple-200">
                                {{ strtoupper(substr($dean->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $dean->name }}</div>
                                <div class="text-[10px] text-purple-500 font-bold uppercase tracking-wider">Dean</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1.5 bg-purple-50 text-purple-700 text-xs font-bold rounded-lg border border-purple-100">
                            {{ $dean->faculty->faculty_name ?? 'No faculty assigned' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $dean->email }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $dean->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-right space-x-1">
                        <button onclick="openEditModal({{ json_encode($dean) }})"
                            class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <form action="{{ route('admin.deans.destroy', $dean->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this dean account?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <p class="text-slate-400 text-sm italic font-medium">No dean accounts yet. Click "Add Dean" to create one.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-purple-700 to-violet-600">
            <h3 class="text-base font-bold text-white">Create Dean Account</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-white/70 hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('admin.deans.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-lg text-xs text-rose-600 font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name</label>
                    <input type="text" name="name" required placeholder="Dr. John Doe"
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone</label>
                    <input type="text" name="phone" placeholder="+1 234 567 890"
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</label>
                <input type="email" name="email" required placeholder="dean@university.edu"
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Faculty Assignment</label>
                <select name="faculty_id" required
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                    <option value="" disabled selected>Select a faculty...</option>
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}">{{ $faculty->faculty_name }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400 mt-1">The dean will only see data from this faculty.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit"
                    class="w-full bg-purple-700 hover:bg-purple-800 text-white font-bold py-3 rounded-xl shadow-lg shadow-purple-500/20 transition-all text-sm uppercase tracking-widest">
                    Create Dean Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-800">
            <h3 class="text-base font-bold text-white">Edit Dean Account</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-white/70 hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Full Name</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone</label>
                    <input type="text" name="phone" id="edit_phone"
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</label>
                <input type="email" name="email" id="edit_email" required
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Faculty Assignment</label>
                <select name="faculty_id" id="edit_faculty_id" required
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm font-medium">
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}">{{ $faculty->faculty_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">New Password <span class="text-slate-300 normal-case">(leave blank to keep current)</span></label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none text-sm font-medium">
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit"
                    class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 rounded-xl transition-all text-sm uppercase tracking-widest">
                    Update Dean
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(dean) {
    document.getElementById('editForm').action = `/admin/deans/${dean.id}`;
    document.getElementById('edit_name').value = dean.name;
    document.getElementById('edit_email').value = dean.email;
    document.getElementById('edit_phone').value = dean.phone || '';
    document.getElementById('edit_faculty_id').value = dean.faculty_id;
    document.getElementById('editModal').classList.remove('hidden');
}

@if($errors->any())
    document.getElementById('addModal').classList.remove('hidden');
@endif
</script>
@endsection
