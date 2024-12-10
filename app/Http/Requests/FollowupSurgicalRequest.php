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
            'alterations'=>'boolean',
			'which_alterations' => 'nullable|string',
            'therapeutic'=>'boolean',
			'which_therapeutic' => 'nullable|string',
            'vomiting'=>'boolean',
			'quantity_vomiting' => 'nullable|string',
            'defecation'=>'boolean',
			'quantity_defecation' => 'nullable|string',
            'urine'=>'boolean',
			'quantity_urine' => 'nullable|string',
            'feeding'=>'boolean',
			'type_feeding' => 'nullable|string',
			'pendings' => 'nullable|string',
            'cleaning'=>'boolean',
			'clean_observations' => 'nullable|string',
            'secretion'=>'boolean',
			'secretion_observations' => 'nullable|string',
            'drainage'=>'boolean',
			'quantity_drainage' => 'nullable|string',
            'blockedages'=>'boolean',
			'type_blocked' => 'nullable|string',
            'infusions'=>'boolean',
			'type_time_infusions' => 'nullable|string',
            'alterations_surgery'=>'boolean',
			'which_alterations_surgery' => 'nullable|string',
			'observations' => 'nullable|string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
