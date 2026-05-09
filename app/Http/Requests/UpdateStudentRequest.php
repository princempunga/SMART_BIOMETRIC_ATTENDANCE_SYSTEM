<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        return ['full_name' => 'required|string|max:255', 'reg_number' => 'required|string|max:255|unique:students,reg_number,'.$this->route('student')->id, 'faculty_id' => 'required|exists:faculties,id', 'department_id' => 'required|exists:departments,id', 'fingerprint_id' => 'required|integer|unique:students,fingerprint_id,'.$this->route('student')->id, 'photo' => 'nullable|image|max:2048',
            //
        ];
    }
}
