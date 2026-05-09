<?php

$entities = [
    'faculty' => [
        'plural' => 'faculties',
        'fields' => ['faculty_name' => 'Text'],
    ],
    'department' => [
        'plural' => 'departments',
        'fields' => ['faculty_id' => 'Select', 'department_name' => 'Text'],
    ],
    'classroom' => [
        'plural' => 'classrooms',
        'fields' => ['room_name' => 'Text'],
    ],
    'device' => [
        'plural' => 'devices',
        'fields' => ['classroom_id' => 'Select', 'device_code' => 'Text', 'device_api_token' => 'Text', 'status' => 'SelectStatus'],
    ],
    'student' => [
        'plural' => 'students',
        'fields' => ['full_name' => 'Text', 'reg_number' => 'Text', 'faculty_id' => 'Select', 'department_id' => 'Select', 'fingerprint_id' => 'Number'],
    ],
    'lecturer' => [
        'plural' => 'lecturers',
        'fields' => ['name' => 'Text', 'email' => 'Email', 'password' => 'Password', 'faculty_id' => 'Select', 'department_id' => 'Select', 'phone' => 'Text'],
    ]
];

foreach ($entities as $singular => $data) {
    $plural = $data['plural'];
    $dir = "resources/views/admin/{$plural}";
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    
    $title = ucfirst($plural);
    $singularTitle = ucfirst($singular);

    // Index View
    $indexHtml = "@extends('layouts.admin')

@section('page_title', 'Manage {$title}')

@section('content')
<div class=\"bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden\">
    <div class=\"p-6 border-b border-slate-200 flex justify-between items-center\">
        <h3 class=\"text-lg font-bold text-slate-800\">{$title} List</h3>
        <a href=\"{{ route('admin.{$plural}.create') }}\" class=\"bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm\">
            + Add New {$singularTitle}
        </a>
    </div>
    <div class=\"overflow-x-auto\">
        <table class=\"w-full text-left border-collapse\">
            <thead>
                <tr class=\"bg-slate-50 text-slate-500 text-xs uppercase tracking-wider\">
                    <th class=\"px-6 py-4 font-semibold\">ID</th>\n";
    foreach ($data['fields'] as $field => $type) {
        $indexHtml .= "                    <th class=\"px-6 py-4 font-semibold\">" . str_replace('_', ' ', ucfirst($field)) . "</th>\n";
    }
    $indexHtml .= "                    <th class=\"px-6 py-4 font-semibold text-right\">Actions</th>
                </tr>
            </thead>
            <tbody class=\"divide-y divide-slate-200\">
                @foreach(\${$plural} as \$item)
                <tr class=\"hover:bg-slate-50 transition-colors\">
                    <td class=\"px-6 py-4 text-sm text-slate-600\">{{ \$item->id }}</td>\n";
    foreach ($data['fields'] as $field => $type) {
        if ($type === 'Select' && strpos($field, '_id') !== false) {
            $rel = str_replace('_id', '', $field);
            $indexHtml .= "                    <td class=\"px-6 py-4 text-sm font-medium text-slate-800\">{{ \$item->{$rel}->name ?? \$item->{$rel}->faculty_name ?? \$item->{$rel}->department_name ?? \$item->{$rel}->room_name ?? 'N/A' }}</td>\n";
        } else {
            $indexHtml .= "                    <td class=\"px-6 py-4 text-sm font-medium text-slate-800\">{{ \$item->{$field} }}</td>\n";
        }
    }
    $indexHtml .= "                    <td class=\"px-6 py-4 text-sm text-right space-x-3\">
                        <a href=\"{{ route('admin.{$plural}.edit', \$item) }}\" class=\"text-blue-600 hover:text-blue-800 font-medium\">Edit</a>
                        <form action=\"{{ route('admin.{$plural}.destroy', \$item) }}\" method=\"POST\" class=\"inline-block\" onsubmit=\"return confirm('Are you sure?');\">
                            @csrf @method('DELETE')
                            <button type=\"submit\" class=\"text-red-600 hover:text-red-800 font-medium\">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection";

    file_put_contents("$dir/index.blade.php", $indexHtml);

    // Create View
    $createHtml = "@extends('layouts.admin')

@section('page_title', 'Add {$singularTitle}')

@section('content')
<div class=\"bg-white rounded-xl shadow-sm border border-slate-200 max-w-2xl mx-auto overflow-hidden\">
    <div class=\"p-6 border-b border-slate-200\">
        <h3 class=\"text-lg font-bold text-slate-800\">Create New {$singularTitle}</h3>
    </div>
    <form action=\"{{ route('admin.{$plural}.store') }}\" method=\"POST\" class=\"p-6 space-y-6\">
        @csrf\n";
    foreach ($data['fields'] as $field => $type) {
        $label = str_replace('_', ' ', ucfirst($field));
        $createHtml .= "        <div>\n            <label class=\"block text-sm font-semibold text-slate-700 mb-2\">{$label}</label>\n";
        if ($type === 'Select' || $type === 'SelectStatus') {
            $createHtml .= "            <select name=\"{$field}\" class=\"w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all\">\n";
            $createHtml .= "                <option value=\"\">Select</option>\n";
            if ($type === 'SelectStatus') {
                $createHtml .= "                <option value=\"active\">Active</option>\n                <option value=\"inactive\">Inactive</option>\n";
            } else {
                $createHtml .= "                @foreach(\\App\\Models\\" . ucfirst(str_replace('_id', '', $field)) . "::all() as \$opt)\n";
                $createHtml .= "                <option value=\"{{ \$opt->id }}\">{{ \$opt->name ?? \$opt->faculty_name ?? \$opt->department_name ?? \$opt->room_name }}</option>\n";
                $createHtml .= "                @endforeach\n";
            }
            $createHtml .= "            </select>\n";
        } else {
            $inputType = $type === 'Password' ? 'password' : ($type === 'Email' ? 'email' : 'text');
            $createHtml .= "            <input type=\"{$inputType}\" name=\"{$field}\" class=\"w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all\" required>\n";
        }
        if ($type === 'Password') {
            $createHtml .= "        </div>\n        <div>\n            <label class=\"block text-sm font-semibold text-slate-700 mb-2\">Confirm Password</label>\n            <input type=\"password\" name=\"password_confirmation\" class=\"w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all\" required>\n";
        }
        $createHtml .= "        </div>\n";
    }
    $createHtml .= "        <div class=\"flex justify-end gap-3 pt-4 border-t border-slate-100\">
            <a href=\"{{ route('admin.{$plural}.index') }}\" class=\"px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-50 rounded-lg transition-colors\">Cancel</a>
            <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors\">Save {$singularTitle}</button>
        </div>
    </form>
</div>
@endsection";

    file_put_contents("$dir/create.blade.php", $createHtml);

    // Edit View (Similar to create but with value binding)
    $editHtml = str_replace("route('admin.{$plural}.store')", "route('admin.{$plural}.update', \$" . $singular . ")", $createHtml);
    $editHtml = str_replace("Create New {$singularTitle}", "Edit {$singularTitle}", $editHtml);
    $editHtml = str_replace("Add {$singularTitle}", "Edit {$singularTitle}", $editHtml);
    $editHtml = str_replace("@csrf", "@csrf @method('PATCH')", $editHtml);
    
    // Quick and dirty replacement for value binding
    foreach ($data['fields'] as $field => $type) {
        if ($type !== 'Select' && $type !== 'SelectStatus' && $type !== 'Password') {
            $editHtml = str_replace("name=\"{$field}\"", "name=\"{$field}\" value=\"{{ \$" . $singular . "->{$field} }}\"", $editHtml);
        }
    }
    file_put_contents("$dir/edit.blade.php", $editHtml);
}
echo "Blade views generated successfully.\n";
