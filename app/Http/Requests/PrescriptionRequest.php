<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
            'veterinarian_id' => 'nullable|integer|exists:users,id',
            'recepcionist_id' => 'nullable|integer|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
			'date' => 'required',
			'medicine' => 'required|string',
			'diagnosis' => 'required|string',
			'observations' => 'string',
            'day_next_check' => 'nullable|date',
            'time_next_check' => 'nullable',
            'reason_next_check_id' => 'nullable|integer|exists:reasons,id',
        ];
    }
}
