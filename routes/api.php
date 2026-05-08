<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\SessionApiController;

Route::post('/clock-in', [AttendanceApiController::class, 'clockIn']);
Route::post('/clock-out', [AttendanceApiController::class, 'clockOut']);
Route::post('/enroll-student', [AttendanceApiController::class, 'enroll']);
Route::post('/start-session', [SessionApiController::class, 'activate']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
