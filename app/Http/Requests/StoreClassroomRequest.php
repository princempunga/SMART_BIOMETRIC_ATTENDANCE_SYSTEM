<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_name' => 'required|string|max:255',
            'room_code' => 'required|string|max:50|unique:classrooms,room_code',
            'building_name' => 'required|string|max:255',
            'floor_number' => 'nullable|string|max:50',
            'seating_capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ];
    }
}
