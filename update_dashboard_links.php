<?php

$file = 'resources/views/admin/dashboard.blade.php';
$content = file_get_contents($file);

$content = str_replace("route('admin.students.create')", "route('admin.students.index')", $content);
$content = str_replace("route('admin.lecturers.create')", "route('admin.lecturers.index')", $content);
$content = str_replace("route('admin.faculties.create')", "route('admin.faculties.index')", $content);
$content = str_replace("route('admin.departments.create')", "route('admin.departments.index')", $content);
$content = str_replace("route('admin.classrooms.create')", "route('admin.classrooms.index')", $content);
$content = str_replace("route('admin.devices.create')", "route('admin.devices.index')", $content);

file_put_contents($file, $content);
echo "Updated.";
