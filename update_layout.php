<?php

$layoutFile = 'resources/views/layouts/admin.blade.php';
$layoutContent = file_get_contents($layoutFile);

$newMenus = "
            <div class=\"group-label\">Academic Structure</div>
            
            <a href=\"{{ route('admin.faculties.index') }}\" class=\"menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}\">
                <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\"></path></svg>
                <span>Faculties</span>
            </a>

            <a href=\"{{ route('admin.departments.index') }}\" class=\"menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}\">
                <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z\"></path></svg>
                <span>Departments</span>
            </a>

            <div class=\"group-label\">Infrastructure</div>

            <a href=\"{{ route('admin.classrooms.index') }}\" class=\"menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}\">
                <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z\"></path></svg>
                <span>Classrooms</span>
            </a>

            <a href=\"{{ route('admin.devices.index') }}\" class=\"menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.devices.*') ? 'active' : '' }}\">
                <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z\"></path></svg>
                <span>ESP32 Devices</span>
            </a>

            <div class=\"group-label\">Users</div>
            
            <a href=\"{{ route('admin.students.index') }}\" class=\"menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.students.*') ? 'active' : '' }}\">
                <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\"></path></svg>
                <span>Students</span>
            </a>

            <a href=\"{{ route('admin.lecturers.index') }}\" class=\"menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.lecturers.*') ? 'active' : '' }}\">
                <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\"></path></svg>
                <span>Lecturers</span>
            </a>
";

$startStr = "<div class=\"group-label\">Management</div>";
$endStr = "<div class=\"group-label\">Analytics</div>";

$startIndex = strpos($layoutContent, $startStr);
$endIndex = strpos($layoutContent, $endStr);

if ($startIndex !== false && $endIndex !== false) {
    $before = substr($layoutContent, 0, $startIndex);
    $after = substr($layoutContent, $endIndex);
    
    $finalLayout = $before . $newMenus . "\n            " . $after;
    file_put_contents($layoutFile, $finalLayout);
    echo "Layout updated successfully.\n";
} else {
    echo "Could not find layout markers.\n";
}
