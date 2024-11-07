<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowUpRequest extends FormRequest
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
            'reception_id' => 'nullable|integer|exists:receptions,id',
			'time' => 'required',
			'details' => 'nullable|string',
			'temperature' => 'nullable|string',
			'systolic' => 'nullable|string',
			'diastolic' => 'nullable|string',
			'average' => 'nullable|string',
			'glycemia_level' => 'nullable|string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
