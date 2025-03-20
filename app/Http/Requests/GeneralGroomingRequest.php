<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralGroomingRequest extends FormRequest
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
            'instructions' => 'nullable|string',
			'next_service' => 'required|date|',
			'critic_status' => 'required|integer',
			'delivery_service' => 'required|integer',
            'delivery_references' => 'nullable|string',
			'folio' => 'nullable|integer',
        ];
    }
}
