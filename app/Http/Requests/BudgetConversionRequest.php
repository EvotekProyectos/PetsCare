<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetConversionRequest extends FormRequest
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
            'budget_detail_ids' => 'required|array|min:1',
            'budget_detail_ids.*' => 'integer|exists:budget_details,id',
        ];
    }

    public function messages(): array
    {
        return [
            'budget_detail_ids.required' => 'Selecciona al menos un servicio para convertir.',
            'budget_detail_ids.min' => 'Selecciona al menos un servicio para convertir.',
        ];
    }
}
