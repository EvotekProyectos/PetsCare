<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurgeryScheduleRequest extends FormRequest
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
            'family_id' => 'nullable|integer|exists:families,id',
            'pet_id' => 'nullable|integer|exists:pets,id',
            'reception_id'=>'nullable|integer|exists:receptions,id',
            'surgical_procedures_type_id' => 'nullable|integer',
			'day' => 'required',
			'hour' => 'required',
            'number_ticket'=> 'required',
            'veterinarian_id' => 'nullable|integer|exists:users,id',
            'status_surgery_id' => 'nullable|integer|exists:status_surgeries,id',
        ];
    }
}
