<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VaccineCertificateRequest extends FormRequest
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
            'service_id' => 'nullable|integer|exists:services,id',
            'product' => 'nullable|string',
            'lab' => 'nullable|string',
            'lote' => 'nullable|string',
            'dose' => 'nullable|string',
            'application_date' => 'nullable|date',
            'last_deworming_date' => 'nullable|date',
            'next_application_date' => 'required|date',
            'observations' => 'nullable|string',
            
        ];
    }
}
