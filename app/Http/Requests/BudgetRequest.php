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
			'total' => 'string',
            'notes' => 'nullable|string',
            'vet_id' => 'nullable|integer|exists:users,id',
            'reception_id' => 'nullable|integer|exists:receptions,id',
        ];
    }
}
