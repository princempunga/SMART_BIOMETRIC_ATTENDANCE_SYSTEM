<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceSession;
use App\Models\Classroom;

class SessionApiController extends Controller
{
    public function activate(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
            'device_code' => 'required|string',
        ]);

        $device = \App\Models\Device::where('device_code', $request->device_code)
            ->where('status', 'active')
            ->first();

        if (!$device) {
            return response()->json(['message' => 'Device not recognized or inactive'], 404);
        }

        $classroom = $device->classroom;

        if (!$classroom) {
            return response()->json(['message' => 'Device not assigned to any classroom'], 404);
        }

        $session = AttendanceSession::where('otp', $request->otp)
            ->where('classroom_id', $classroom->id)
            ->where('status', 'pending')
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Invalid OTP or session already active/expired'], 404);
        }

        $session->update(['status' => 'active']);

        return response()->json([
            'status' => 'success',
            'message' => 'Session Activated',
            'session_id' => $session->id,
            'session_token' => bin2hex(random_bytes(16)), // Simple token for persistence
            'course' => $session->course->course_name,
            'lecturer' => $session->lecturer->name,
            'classroom' => $classroom->room_name,
            'start_time' => $session->session_start,
            'expiry_time' => \Carbon\Carbon::parse($session->session_start)->addHours(3)->toDateTimeString(), // Example 3hr limit
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'device_code' => 'required|string',
        ]);

        $device = \App\Models\Device::where('device_code', $request->device_code)
            ->where('status', 'active')
            ->first();

        if (!$device) {
            return response()->json(['message' => 'Device unauthorized'], 401);
        }

        $session = AttendanceSession::where('id', $request->session_id)
            ->where('classroom_id', $device->classroom_id)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Session expired or not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Session Restored',
            'session_id' => $session->id,
            'course' => $session->course->course_name,
            'lecturer' => $session->lecturer->name,
            'classroom' => $session->classroom->room_name,
            'active_students' => $session->attendanceLogs()->count()
        ]);
    }
}
