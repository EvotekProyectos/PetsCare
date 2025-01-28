<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceptionRequest extends FormRequest
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

            'pet_id' => 'required|integer|exists:pets,id',
            'reception_type_id' => 'required|integer|exists:reception_types,id',
			'admission_type_id' => 'nullable|integer|exists:admission_types,id',
            'area_id' => 'nullable|integer|exists:areas,id',
            'family_id' => 'nullable|integer|exists:families,id',
            'reason_id' => 'nullable|integer|exists:reasons,id',
            'veterinarian_id' => 'required|integer|exists:users,id',
            'recepcionist_id' => 'nullable|integer|exists:users,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'entry_date' => 'required|date|',
            'exit_date'=>'nullable|date|',
            'num'=>'nullable|integer',
            
        ];
    }
}
