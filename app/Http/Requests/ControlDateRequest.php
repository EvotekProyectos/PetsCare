<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ControlDateRequest extends FormRequest
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
            'family_id' => 'nullable|integer|exists:families,id',
            'pet_id' => 'nullable|integer|exists:pets,id',
            'date_type_id' => 'nullable|integer|exists:date_types,id',
            'status_type_id' => 'nullable|integer|exists:status_dates,id',
			'date' => 'required',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
