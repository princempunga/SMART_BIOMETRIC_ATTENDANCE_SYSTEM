<?php

$routes = file_get_contents('routes/web.php');

$newAdminGroup = "Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    
    Route::resource('faculties', App\Http\Controllers\Admin\FacultyController::class);
    Route::resource('departments', App\Http\Controllers\Admin\DepartmentController::class);
    Route::resource('classrooms', App\Http\Controllers\Admin\ClassroomController::class);
    Route::resource('devices', App\Http\Controllers\Admin\DeviceController::class);
    Route::resource('students', App\Http\Controllers\Admin\StudentController::class);
    Route::resource('lecturers', App\Http\Controllers\Admin\LecturerController::class);
    
    // Existing routes that are not part of Phase 1 CRUD
    Route::get('/courses', [App\Http\Controllers\AdminController::class, 'courses'])->name('courses');
    Route::post('/courses', [App\Http\Controllers\AdminController::class, 'storeCourse'])->name('courses.store');
    Route::patch('/courses/{course}', [App\Http\Controllers\AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [App\Http\Controllers\AdminController::class, 'destroyCourse'])->name('courses.destroy');
    
    Route::get('/attendance', [App\Http\Controllers\AdminController::class, 'attendance'])->name('attendance');
    Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [App\Http\Controllers\AdminController::class, 'generateReport'])->name('reports.generate');
});";

$startStr = "Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {";
$endStr = "Route::middleware(['auth', 'verified'])->prefix('lecturer')->group(function () {";

$startIndex = strpos($routes, $startStr);
$endIndex = strpos($routes, $endStr);

if ($startIndex !== false && $endIndex !== false) {
    $before = substr($routes, 0, $startIndex);
    $after = substr($routes, $endIndex);
    
    $finalRoutes = $before . $newAdminGroup . "\n\n" . $after;
    file_put_contents('routes/web.php', $finalRoutes);
    echo "Routes updated successfully.\n";
} else {
    echo "Could not find route group markers.\n";
}
