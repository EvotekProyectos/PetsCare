<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
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
            'pet_id' => 'nullable|integer|exists:pets,id',
			'date' => 'required|date',
            'surgery_pack_id' => 'nullable|integer|exists:surgery_packs,id',
			'procedure' => 'required|string',
			'procedure_price' => 'required',
            'biometric' => 'string',
			'biometric_price' => 'nullable|string',
            'chemistry' => 'string',
			'chemistry_price' => 'nullable|string',
            'nodulectomy' => 'string',
			'nodulectomy_price' => 'nullable|string',
            'histopathology' => 'string',
			'histopathology_price' => 'nullable|string',
            'xrays' => 'string',
			'xrays_price' => 'nullable|string',
            'collar' => 'string',
			'collar_price' => 'nullable|string',
            'body' => 'string',
			'body_price' => 'nullable|string',
			'others' => 'string',
			'total' => 'string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
