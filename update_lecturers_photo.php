<?php
$file = 'resources/views/admin/lecturers/index.blade.php';
$content = file_get_contents($file);

// Add headers
$content = str_replace('<th class="px-6 py-4">Name</th>', '<th class="px-6 py-4">Photo</th>
                        <th class="px-6 py-4">Name</th>', $content);
$content = str_replace('<th class="px-6 py-4">Phone</th>', '<th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Phone</th>', $content);

// Add table data
$tdPhoto = '<td class="px-6 py-4">
                            @if($item->profile_photo)
                                <img src="{{ asset(\'storage/\' . $item->profile_photo) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item->name }}</td>';
$content = str_replace('<td class="px-6 py-4 text-sm text-[#475569]">{{ $item->name }}</td>', $tdPhoto, $content);

$content = str_replace('<td class="px-6 py-4 text-sm text-[#475569]">{{ $item->phone }}</td>', '<td class="px-6 py-4 text-sm text-[#475569]">{{ $item->subject }}</td>
                        <td class="px-6 py-4 text-sm text-[#475569]">{{ $item->phone }}</td>', $content);


// Add enctype to forms
$content = str_replace('<form action="{{ route(\'admin.lecturers.store\') }}" method="POST" class="p-8 space-y-6">', '<form action="{{ route(\'admin.lecturers.store\') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">', $content);
$content = str_replace('<form id="editForm_lecturers" method="POST" class="p-8 space-y-6">', '<form id="editForm_lecturers" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">', $content);

// Add profile photo and subject input to Modals
$photoInput = '            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/*" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
';
$subjectInput = '            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subject Specialization</label>
                <input type="text" name="subject" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
';
$editSubjectInput = '            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subject Specialization</label>
                <input type="text" name="subject" id="edit_subject_lecturers" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none font-medium">
            </div>
';

// Insert Photo into Add form (after @csrf)
$content = preg_replace('/(@csrf\s+)(<div)/', "$1$photoInput$2", $content, 1);
// Insert Photo into Edit form (after @method('PATCH'))
$content = preg_replace('/(@method\(\'PATCH\'\)\s+)(<div)/', "$1$photoInput$2", $content, 1);

// Insert Subject before Phone in Add
$content = str_replace('<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</label>
                <input type="text" name="phone"', $subjectInput . '<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</label>
                <input type="text" name="phone"', $content);

// Insert Subject before Phone in Edit
$content = str_replace('<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</label>
                <input type="text" name="phone" id="edit_phone_lecturers"', $editSubjectInput . '<div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</label>
                <input type="text" name="phone" id="edit_phone_lecturers"', $content);

// Update JS function
$content = str_replace('if(document.getElementById(\'edit_phone_lecturers\')) document.getElementById(\'edit_phone_lecturers\').value = item.phone;', 'if(document.getElementById(\'edit_phone_lecturers\')) document.getElementById(\'edit_phone_lecturers\').value = item.phone;
        if(document.getElementById(\'edit_subject_lecturers\')) document.getElementById(\'edit_subject_lecturers\').value = item.subject;', $content);

file_put_contents($file, $content);
echo "Updated lecturers blade file.";
