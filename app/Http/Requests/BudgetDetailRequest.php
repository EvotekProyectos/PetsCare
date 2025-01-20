<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetDetailRequest extends FormRequest
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
            'budget_id' => 'nullable|integer|exists:budgets,id',
			'service_id' => 'nullable|string',
            'img_id' => 'nullable|string',
            'lab_id' => 'nullable|string',
			'price' => 'required|string',
			'notes' => 'nullable|string',
            'type' => 'nullable|string',
        ];
    }
}
