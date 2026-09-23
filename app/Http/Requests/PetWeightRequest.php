<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetWeightRequest extends FormRequest
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
            'reception_id' => 'nullable|integer|exists:receptions,id',
            'weight' => 'required|numeric|gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'weight.required' => 'El peso es obligatorio.',
            'weight.numeric' => 'El peso debe ser un número.',
            'weight.gt' => 'El peso debe ser mayor a 0.',
        ];
    }

    public function attributes(): array
    {
        return [
            'pet_id' => 'mascota',
            'reception_id' => 'recepción',
            'weight' => 'peso',
        ];
    }
}
