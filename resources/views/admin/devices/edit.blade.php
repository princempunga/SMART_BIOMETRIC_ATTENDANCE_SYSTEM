@extends('layouts.admin')

@section('page_title', 'Edit Device')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-2xl mx-auto overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Edit Device</h3>
    </div>
    <form action="{{ route('admin.devices.update', $device) }}" method="POST" class="p-6 space-y-6">
        @csrf @method('PATCH')
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Classroom id</label>
            <select name="classroom_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all">
                <option value="">Select</option>
                @foreach(\App\Models\Classroom::all() as $opt)
                <option value="{{ $opt->id }}">{{ $opt->name ?? $opt->faculty_name ?? $opt->department_name ?? $opt->room_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Device code</label>
            <input type="text" name="device_code" value="{{ $device->device_code }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Device api token</label>
            <input type="text" name="device_api_token" value="{{ $device->device_api_token }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all">
                <option value="">Select</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.devices.index') }}" class="px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-50 rounded-lg transition-colors">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors">Save Device</button>
        </div>
    </form>
</div>
@endsection