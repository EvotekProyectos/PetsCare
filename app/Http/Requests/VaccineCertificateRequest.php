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
			'vaccine' => 'string',
			'lab' => 'string',
			'lote' => 'string',
			'observations_vaccine' => 'string',
			'product_internal' => 'string',
			'dose_internal' => 'string',
			'next_internal_date' => 'required',
			'observations_internal' => 'string',
			'product_external' => 'string',
			'dose_external' => 'string',
			'next_external_date' => 'required',
			'observations_external' => 'string',
        ];
    }
}
