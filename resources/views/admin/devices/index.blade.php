@extends('layouts.admin')

@section('page_title', 'ESP32 Devices')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div>
            <h3 class="font-bold text-[#0F172A]">Biometric Hardware Management</h3>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Manage ESP32 Attendance Terminals</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Register Device</span>
        </button>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-100 text-rose-600 px-4 py-3 rounded-lg text-xs font-bold">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Hardware Info</th>
                        <th class="px-6 py-4">Assigned Location</th>
                        <th class="px-6 py-4">API Authentication</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-[#0F172A]">{{ $item->device_code }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-tighter">ESP32-WROOM-32</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->classroom)
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-600">{{ $item->classroom->room_name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $item->classroom->building_name }}</span>
                                </div>
                            @else
                                <span class="text-[10px] font-bold text-rose-400 uppercase tracking-widest italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="group relative">
                                <span class="text-xs font-mono bg-slate-50 px-2 py-1 rounded border border-slate-100 text-slate-500 cursor-pointer hover:bg-white transition-all">
                                    ••••••••{{ substr($item->device_api_token, -4) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $item->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal_devices({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L12 18.75H8.25V15L17.586 5.686z"></path></svg>
                                </button>
                                <form action="{{ route('admin.devices.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this device?')">
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
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-400">No hardware devices registered yet.</span>
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
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Register New Device</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.devices.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Hardware Identifier (Code)</label>
                <input type="text" name="device_code" required placeholder="e.g. ESP-BLOCK-A-01" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Assigned Classroom</label>
                <select name="classroom_id" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    <option value="" disabled selected>Choose Location...</option>
                    @foreach($classrooms as $room)
                    <option value="{{ $room->id }}">{{ $room->room_name }} ({{ $room->room_code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">API Security Token</label>
                <div class="relative">
                    <input type="text" name="device_api_token" id="add_device_api_token" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm pr-20">
                    <button type="button" onclick="generateToken('add_device_api_token')" class="absolute right-2 top-2 text-[9px] bg-white border border-slate-200 text-slate-600 px-2 py-1 rounded shadow-sm hover:bg-slate-50 font-bold uppercase tracking-widest">Generate</button>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    <option value="active">Active & Online</option>
                    <option value="inactive">Inactive / Offline</option>
                </select>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-lg shadow-xl shadow-blue-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Register Terminal</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#0F172A]">Edit Terminal Settings</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="editForm_devices" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Hardware Identifier (Code)</label>
                <input type="text" name="device_code" id="edit_device_code" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Assigned Classroom</label>
                <select name="classroom_id" id="edit_classroom_id" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    @foreach($classrooms as $room)
                    <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">API Security Token</label>
                <input type="text" name="device_api_token" id="edit_device_api_token" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status</label>
                <select name="status" id="edit_status" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-sm">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold py-3 rounded-lg shadow-xl shadow-blue-500/20 transition-all transform active:scale-[0.98]">
                    Apply Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function generateToken(targetId) {
        const token = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        document.getElementById(targetId).value = 'esp_' + token.toUpperCase();
    }

    function openEditModal_devices(item) {
        document.getElementById('editForm_devices').action = `/admin/devices/${item.id}`;
        document.getElementById('edit_device_code').value = item.device_code;
        document.getElementById('edit_classroom_id').value = item.classroom_id;
        document.getElementById('edit_device_api_token').value = item.device_api_token;
        document.getElementById('edit_status').value = item.status;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection