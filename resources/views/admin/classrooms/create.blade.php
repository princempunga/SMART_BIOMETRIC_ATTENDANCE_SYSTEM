@extends('layouts.admin')

@section('page_title', 'Add Classroom')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-2xl mx-auto overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Create New Classroom</h3>
    </div>
    <form action="{{ route('admin.classrooms.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Room name</label>
            <input type="text" name="room_name" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all" required>
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.classrooms.index') }}" class="px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-50 rounded-lg transition-colors">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors">Save Classroom</button>
        </div>
    </form>
</div>
@endsection