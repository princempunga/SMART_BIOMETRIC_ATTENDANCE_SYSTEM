@extends('layouts.admin')

@section('page_title', 'Classrooms')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div>
            <h3 class="font-bold text-[#0F172A]">Classrooms Management</h3>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Manage Physical Infrastructure</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Classroom</span>
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-lg text-sm font-bold animate-pulse">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Room info</th>
                        <th class="px-6 py-4">Building</th>
                        <th class="px-6 py-4">Capacity</th>
                        <th class="px-6 py-4">Device Status</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classrooms as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-[#0F172A]">{{ $item->room_name }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $item->room_code }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-slate-600 font-medium">{{ $item->building_name }}</span>
                                <span class="text-[10px] text-slate-400">Floor {{ $item->floor_number ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600">
                                {{ $item->seating_capacity }} Seats
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->device)
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Linked: {{ $item->device->device_code }}
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 uppercase">No Device Linked</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $item->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal_classrooms({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L12 18.75H8.25V15L17.586 5.686z"></path></svg>
                                </button>
                                <form action="{{ route('admin.classrooms.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this classroom? This may affect linked devices.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-400">No classrooms registered yet.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Register New Classroom</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.classrooms.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Room Name</label>
                    <input type="text" name="room_name" required placeholder="e.g. Engineering Hall A" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Room Code</label>
                    <input type="text" name="room_code" required placeholder="e.g. EHA-01" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Building</label>
                    <input type="text" name="building_name" required placeholder="e.g. Block B" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Floor (Optional)</label>
                    <input type="text" name="floor_number" placeholder="e.g. Ground" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Capacity</label>
                    <input type="number" name="seating_capacity" required placeholder="50" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Operational Status</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                        <option value="active">Active & Operational</option>
                        <option value="inactive">Inactive / Maintenance</option>
                    </select>
                </div>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-lg shadow-xl shadow-blue-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Classroom</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Edit Classroom</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm_classrooms" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Room Name</label>
                    <input type="text" name="room_name" id="edit_room_name" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Room Code</label>
                    <input type="text" name="room_code" id="edit_room_code" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Building</label>
                    <input type="text" name="building_name" id="edit_building_name" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Floor</label>
                    <input type="text" name="floor_number" id="edit_floor_number" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Capacity</label>
                    <input type="number" name="seating_capacity" id="edit_seating_capacity" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Operational Status</label>
                    <select name="status" id="edit_status" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-lg shadow-xl shadow-blue-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Apply Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal_classrooms(item) {
        document.getElementById('editForm_classrooms').action = `/admin/classrooms/${item.id}`;
        document.getElementById('edit_room_name').value = item.room_name;
        document.getElementById('edit_room_code').value = item.room_code || '';
        document.getElementById('edit_building_name').value = item.building_name || '';
        document.getElementById('edit_floor_number').value = item.floor_number || '';
        document.getElementById('edit_seating_capacity').value = item.seating_capacity || '';
        document.getElementById('edit_status').value = item.status;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection