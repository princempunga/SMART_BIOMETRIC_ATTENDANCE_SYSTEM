<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\SessionApiController;

Route::post('/clock-in', [AttendanceApiController::class, 'clockIn']);
Route::post('/clock-out', [AttendanceApiController::class, 'clockOut']);
Route::post('/enroll-student', [AttendanceApiController::class, 'enroll']);
Route::post('/start-session', [SessionApiController::class, 'activate']);
Route::post('/restore-session', [SessionApiController::class, 'restore']);
Route::post('/sync-attendance', [AttendanceApiController::class, 'sync']);
Route::get('/student-cache', [AttendanceApiController::class, 'getStudentCache']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
