<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_code' => 'required|string|unique:devices,device_code,' . $this->device->id,
            'device_api_token' => 'required|string|unique:devices,device_api_token,' . $this->device->id,
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Device::where('classroom_id', $value)
                        ->where('status', 'active')
                        ->where('id', '!=', $this->device->id)
                        ->exists();
                    if ($exists && $this->status === 'active') {
                        $fail('This classroom already has another active device assigned.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ];
    }
}
