<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'dean') {
            return redirect()->route('dean.dashboard');
        }
        return redirect()->route('lecturer.dashboard');
    }
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
    Route::resource('deans', App\Http\Controllers\Admin\DeanController::class);
    
    // Existing routes that are not part of Phase 1 CRUD
    Route::get('/courses', [App\Http\Controllers\AdminController::class, 'courses'])->name('courses');
    Route::post('/courses', [App\Http\Controllers\AdminController::class, 'storeCourse'])->name('courses.store');
    Route::patch('/courses/{course}', [App\Http\Controllers\AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [App\Http\Controllers\AdminController::class, 'destroyCourse'])->name('courses.destroy');
    
    Route::get('/attendance', [App\Http\Controllers\AttendanceLogsController::class, 'index'])->name('attendance');
    Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [App\Http\Controllers\AdminController::class, 'generateReport'])->name('reports.generate');
    Route::get('/simulate-data', [App\Http\Controllers\AttendanceSimulationController::class, 'seedDemoData'])->name('simulate-data');
    
    Route::resource('course-units', App\Http\Controllers\Admin\CourseUnitController::class);
    Route::get('/get-departments/{faculty}', [App\Http\Controllers\AdminController::class, 'getDepartments'])->name('get-departments');
    Route::get('/api/faculties/{faculty}/course-units', [App\Http\Controllers\Admin\CourseUnitController::class, 'getByFaculty']);
});

Route::middleware(['auth', 'verified', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\LecturerController::class, 'index'])->name('dashboard');
    Route::get('/courses', [App\Http\Controllers\LecturerController::class, 'courses'])->name('courses');
    Route::get('/sessions', [App\Http\Controllers\LecturerController::class, 'sessions'])->name('sessions');
    Route::get('/sessions/{session}/logs', [App\Http\Controllers\LecturerController::class, 'getAttendanceLogs'])->name('sessions.logs');
    Route::get('/attendance', [App\Http\Controllers\LecturerController::class, 'attendance'])->name('attendance');
    Route::get('/reports', [App\Http\Controllers\LecturerController::class, 'reports'])->name('reports');
    Route::get('/reports/download', [App\Http\Controllers\LecturerController::class, 'downloadReport'])->name('reports.download');
    
    // New Session Routes
    Route::post('/sessions/start', [App\Http\Controllers\LecturerController::class, 'startSession'])->name('sessions.start');
    Route::get('/sessions/{session}/active', [App\Http\Controllers\LecturerController::class, 'activeSession'])->name('sessions.active');
    Route::post('/sessions/{session}/verify', [App\Http\Controllers\LecturerController::class, 'verifyOtp'])->name('sessions.verify');
    Route::post('/sessions/{session}/complete', [App\Http\Controllers\LecturerController::class, 'completeSession'])->name('sessions.complete');
    Route::delete('/sessions/{session}', [App\Http\Controllers\LecturerController::class, 'destroySession'])->name('sessions.destroy');
    Route::get('/sessions/{session}/live-data', [App\Http\Controllers\LecturerController::class, 'getLiveData'])->name('sessions.live-data');

    // Simulation Routes
    Route::get('/test-environment', [App\Http\Controllers\AttendanceSimulationController::class, 'index'])->name('test-environment');
    Route::post('/simulate-scan', [App\Http\Controllers\AttendanceSimulationController::class, 'simulateScan'])->name('simulate-scan');
});

Route::middleware(['auth', 'verified', 'role:dean'])->prefix('dean')->name('dean.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DeanController::class, 'index'])->name('dashboard');
    Route::get('/students', [App\Http\Controllers\DeanController::class, 'students'])->name('students');
    Route::post('/students', [App\Http\Controllers\DeanController::class, 'storeStudent'])->name('students.store');
    Route::patch('/students/{student}', [App\Http\Controllers\DeanController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [App\Http\Controllers\DeanController::class, 'destroyStudent'])->name('students.destroy');
    
    Route::get('/lecturers', [App\Http\Controllers\DeanController::class, 'lecturers'])->name('lecturers');
    Route::get('/attendance', [App\Http\Controllers\DeanController::class, 'attendance'])->name('attendance');
    Route::get('/reports', [App\Http\Controllers\DeanController::class, 'reports'])->name('reports');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
