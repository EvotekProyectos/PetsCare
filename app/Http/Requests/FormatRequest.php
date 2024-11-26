<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatRequest extends FormRequest
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
            'format_type_id' => 'nullable|integer|exists:format_types,id',
			'reception_id' => 'nullable|integer|exists:receptions,id',
            'pet_id' => 'nullable|integer|exists:pets,id',
            'format_pdf' => 'required|string',
        ];
    }
}
