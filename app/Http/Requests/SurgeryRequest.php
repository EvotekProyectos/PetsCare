<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurgeryRequest extends FormRequest
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
            'surgery_type_id' => 'nullable|integer|exists:product_types,id',
            'surgery_date'=>'required',
            'surgery_description'=> 'string',
			'preanesthetic' => 'string',
			'anesthetic' => 'string',
			'other_medicines' => 'string',
            'treatment'=> 'string',
            'observations'=> 'string',
            'complications'=> 'string',
            'vet_id'=> 'nullable|integer|exists:users,id',
        ];
    }
}
