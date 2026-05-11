<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
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
    Route::get('/simulate-data', [App\Http\Controllers\AttendanceSimulationController::class, 'seedDemoData'])->name('simulate-data');
    
    Route::resource('course-units', App\Http\Controllers\Admin\CourseUnitController::class);
    Route::get('/api/faculties/{faculty}/course-units', [App\Http\Controllers\Admin\CourseUnitController::class, 'getByFaculty']);
});

Route::middleware(['auth', 'verified', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\LecturerController::class, 'index'])->name('dashboard');
    Route::get('/courses', [App\Http\Controllers\LecturerController::class, 'courses'])->name('courses');
    Route::get('/sessions', [App\Http\Controllers\LecturerController::class, 'sessions'])->name('sessions');
    Route::get('/sessions/{session}/logs', [App\Http\Controllers\LecturerController::class, 'getAttendanceLogs'])->name('sessions.logs');
    Route::get('/attendance', [App\Http\Controllers\LecturerController::class, 'attendance'])->name('attendance');
    Route::get('/reports', [App\Http\Controllers\LecturerController::class, 'reports'])->name('reports');
    
    // New Session Routes
    Route::post('/sessions/start', [App\Http\Controllers\LecturerController::class, 'startSession'])->name('sessions.start');
    Route::get('/sessions/{session}/active', [App\Http\Controllers\LecturerController::class, 'activeSession'])->name('sessions.active');
    Route::post('/sessions/{session}/verify', [App\Http\Controllers\LecturerController::class, 'verifyOtp'])->name('sessions.verify');
    Route::post('/sessions/{session}/complete', [App\Http\Controllers\LecturerController::class, 'completeSession'])->name('sessions.complete');
    Route::get('/sessions/{session}/count', [App\Http\Controllers\LecturerController::class, 'getAttendanceCount'])->name('sessions.count');

    // Simulation Routes
    Route::get('/test-environment', [App\Http\Controllers\AttendanceSimulationController::class, 'index'])->name('test-environment');
    Route::post('/simulate-scan', [App\Http\Controllers\AttendanceSimulationController::class, 'simulateScan'])->name('simulate-scan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
