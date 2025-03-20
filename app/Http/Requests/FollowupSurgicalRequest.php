<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowupSurgicalRequest extends FormRequest
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
            'cleaning'=>'required|boolean',
			'clean_observations' => 'nullable|string',
            'secretion'=>'required|boolean',
			'secretion_observations' => 'nullable|string',
            'drainage'=>'required|boolean',
			'quantity_drainage' => 'nullable|string',
            'blockedages'=>'required|boolean',
			'type_blocked' => 'nullable|string',
            'infusions'=>'required|boolean',
			'type_time_infusions' => 'nullable|string',
            'alterations_surgery'=>'required|boolean',
			'which_alterations_surgery' => 'nullable|string',
			'observations' => 'nullable|string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
