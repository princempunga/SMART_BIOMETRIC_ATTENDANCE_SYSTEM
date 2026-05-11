<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_code' => 'required|string|unique:devices,device_code',
            'device_api_token' => 'required|string|unique:devices,device_api_token',
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Device::where('classroom_id', $value)
                        ->where('status', 'active')
                        ->exists();
                    if ($exists && $this->status === 'active') {
                        $fail('This classroom already has an active device assigned.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ];
    }
}
