<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['classroom_id' => 'nullable|exists:classrooms,id', 'device_code' => 'required|string|unique:devices,device_code|max:255', 'device_api_token' => 'required|string|unique:devices,device_api_token|max:255', 'status' => 'required|in:active,inactive',
            //
        ];
    }
}
