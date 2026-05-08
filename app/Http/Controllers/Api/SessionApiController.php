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
            'device_id' => 'required|string',
        ]);

        $classroom = Classroom::where('device_id', $request->device_id)->first();

        if (!$classroom) {
            return response()->json(['message' => 'Device not recognized'], 404);
        }

        $session = AttendanceSession::where('otp', $request->otp)
            ->where('classroom_id', $classroom->id)
            ->whereNull('session_end')
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Invalid OTP or no active session'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Activated',
            'session_id' => $session->id,
            'course' => $session->course->course_name,
            'lecturer' => $session->lecturer->name
        ]);
    }
}
