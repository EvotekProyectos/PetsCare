<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
            'vaccine_certificate_id' => 'nullable|integer|exists:vaccine_certificates,id',
			'food' => 'required|string',
			'objects' => 'required|string',
			'observations' => 'required|string',
			'number_days' => 'required',
            'service_type_id' => 'required',
            'cubicle_id' => 'nullable|integer|exists:cubicles,id',
            
        ];
    }
}
