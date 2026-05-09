<?php
$file = 'resources/views/admin/students/index.blade.php';
$content = file_get_contents($file);

// Add Photo Column to table head
$content = str_replace('<th class="px-6 py-4">Full Name</th>', '<th class="px-6 py-4">Photo</th>
                        <th class="px-6 py-4">Full Name</th>', $content);

// Add Photo data to table body
$tdPhoto = '<td class="px-6 py-4">
                            @if($item->photo)
                                <img src="{{ asset(\'storage/\' . $item->photo) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item->full_name }}</td>';
$content = str_replace('<td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item->full_name }}</td>', $tdPhoto, $content);

// Add enctype to forms
$content = str_replace('<form action="{{ route(\'admin.students.store\') }}" method="POST" class="p-8 space-y-6">', '<form action="{{ route(\'admin.students.store\') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">', $content);
$content = str_replace('<form id="editForm_students" method="POST" class="p-8 space-y-6">', '<form id="editForm_students" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">', $content);

// Add file input to Add Modal
$photoInput = '            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Student Photo</label>
                <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium">
            </div>
';
// Insert into Add form (after @csrf)
$content = preg_replace('/(@csrf\s+)(<div)/', "$1$photoInput$2", $content, 1);

// Insert into Edit form (after @method('PATCH'))
$content = preg_replace('/(@method\(\'PATCH\'\)\s+)(<div)/', "$1$photoInput$2", $content, 1);

file_put_contents($file, $content);
echo "Updated students blade file.";
