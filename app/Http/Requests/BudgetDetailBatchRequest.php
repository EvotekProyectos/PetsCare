<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetDetailBatchRequest extends FormRequest
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
            'reception_id' => 'required|integer|exists:receptions,id',
            'lines' => 'required|array|min:1',
            'lines.*.type' => 'required|string|in:service,lab,img',
            'lines.*.product_id' => 'required|integer',
            'lines.*.price' => 'required|string',
            'lines.*.notes' => 'nullable|string',
        ];
    }
}
