<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
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
            'family_id' => 'required|integer|exists:families,id',
			'name' => 'required|string',
            'number_chip'=>'nullable|string',
            'picture_id' => 'nullable|integer|exists:files,id',
			'specie' => 'required|string',
			'raza' => 'nullable|string',
            'gender_id' => 'required|integer|exists:genres,id',
            'birthday' => 'nullable|date',
            'reproductive_status_id' => 'required|integer|exists:reproductive_statuses,id',
			'weight' => 'nullable|string',
			'physic_descrip' => 'nullable|string',
			'notes' => 'nullable|string',
            'pet_classification_id' => 'nullable|integer|exists:pet_classifications,id',
            'deceased' => 'required|boolean',

        ];
    }
}
