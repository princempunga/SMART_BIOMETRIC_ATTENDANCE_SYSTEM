<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->middleware('role:admin')->name('admin.dashboard');
    
    // Management
    Route::get('/students', [App\Http\Controllers\AdminController::class, 'students'])->middleware('role:admin')->name('admin.students');
    Route::post('/students', [App\Http\Controllers\AdminController::class, 'storeStudent'])->middleware('role:admin')->name('admin.students.store');
    Route::patch('/students/{student}', [App\Http\Controllers\AdminController::class, 'updateStudent'])->middleware('role:admin')->name('admin.students.update');
    Route::delete('/students/{student}', [App\Http\Controllers\AdminController::class, 'destroyStudent'])->middleware('role:admin')->name('admin.students.destroy');
    
    Route::get('/lecturers', [App\Http\Controllers\AdminController::class, 'lecturers'])->middleware('role:admin')->name('admin.lecturers');
    Route::get('/departments/{facultyId}', [App\Http\Controllers\AdminController::class, 'getDepartments'])->middleware('role:admin');
    Route::post('/lecturers', [App\Http\Controllers\AdminController::class, 'storeLecturer'])->middleware('role:admin')->name('admin.lecturers.store');
    Route::patch('/lecturers/{user}', [App\Http\Controllers\AdminController::class, 'updateLecturer'])->middleware('role:admin')->name('admin.lecturers.update');
    Route::delete('/lecturers/{user}', [App\Http\Controllers\AdminController::class, 'destroyLecturer'])->middleware('role:admin')->name('admin.lecturers.destroy');
    
    Route::get('/courses', [App\Http\Controllers\AdminController::class, 'courses'])->middleware('role:admin')->name('admin.courses');
    Route::post('/courses', [App\Http\Controllers\AdminController::class, 'storeCourse'])->middleware('role:admin')->name('admin.courses.store');
    Route::patch('/courses/{course}', [App\Http\Controllers\AdminController::class, 'updateCourse'])->middleware('role:admin')->name('admin.courses.update');
    Route::delete('/courses/{course}', [App\Http\Controllers\AdminController::class, 'destroyCourse'])->middleware('role:admin')->name('admin.courses.destroy');
    
    Route::get('/classrooms', [App\Http\Controllers\AdminController::class, 'classrooms'])->middleware('role:admin')->name('admin.classrooms');
    Route::post('/classrooms', [App\Http\Controllers\AdminController::class, 'storeClassroom'])->middleware('role:admin')->name('admin.classrooms.store');
    Route::patch('/classrooms/{classroom}', [App\Http\Controllers\AdminController::class, 'updateClassroom'])->middleware('role:admin')->name('admin.classrooms.update');
    Route::delete('/classrooms/{classroom}', [App\Http\Controllers\AdminController::class, 'destroyClassroom'])->middleware('role:admin')->name('admin.classrooms.destroy');
    
    // Analytics
    Route::get('/attendance', [App\Http\Controllers\AdminController::class, 'attendance'])->middleware('role:admin')->name('admin.attendance');
    Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->middleware('role:admin')->name('admin.reports');
    Route::post('/reports/generate', [App\Http\Controllers\AdminController::class, 'generateReport'])->middleware('role:admin')->name('admin.reports.generate');
});

Route::middleware(['auth', 'verified'])->prefix('lecturer')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\LecturerController::class, 'index'])->middleware('role:lecturer')->name('lecturer.dashboard');
    Route::get('/courses', [App\Http\Controllers\LecturerController::class, 'courses'])->middleware('role:lecturer')->name('lecturer.courses');
    Route::get('/sessions', [App\Http\Controllers\LecturerController::class, 'sessions'])->middleware('role:lecturer')->name('lecturer.sessions');
    Route::get('/attendance', [App\Http\Controllers\LecturerController::class, 'attendance'])->middleware('role:lecturer')->name('lecturer.attendance');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
