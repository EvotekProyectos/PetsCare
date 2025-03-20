<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowupInternRequest extends FormRequest
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
			'date' => 'required',
            'alterations'=>'required|boolean',
			'which_alterations' => 'nullable|string',
            'therapeutic'=>'required|boolean',
			'which_therapeutic' => 'nullable|string',
            'vomiting'=>'required|boolean',
			'quantity_vomiting' => 'nullable|string',
            'defecation'=>'required|boolean',
			'quantity_defecation' => 'nullable|string',
            'urine'=>'required|boolean',
			'quantity_urine' => 'nullable|string',
            'feeding'=>'required|boolean',
			'type_feeding' => 'nullable|string',
			'pendings' => 'nullable|string',
            'ultrasounds'=>'required|boolean',
			'observations_ultrasounds' => 'nullable|string',
			'observations' => 'string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
