<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLecturerRequest extends FormRequest
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
        return ['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email,'.$this->route('lecturer')->id, 'password' => 'nullable|string|min:8', 'faculty_id' => 'nullable|exists:faculties,id', 'department_id' => 'nullable|exists:departments,id', 'phone' => 'nullable|string|max:20', 'subject' => 'nullable|string|max:255', 'profile_photo' => 'nullable|image|max:2048',
            //
        ];
    }
}
