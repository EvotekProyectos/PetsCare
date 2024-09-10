<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'begin' => 'required|date',
			'end' => 'required|date',
            'shift_id' => 'nullable|integer|exists:shifts,id',
            'user_id' => 'required|integer|exists:users,id',
            'cover_area_id' => 'nullable|integer|exists:cover_areas,id'
        ];
    }
}
