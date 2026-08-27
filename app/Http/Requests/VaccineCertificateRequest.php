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
        // store() (POST, desde el modal de Consulta): vaccine-certificate/form.blade.php
        // manda un lote de aplicaciones (vacuna + desparasitaciones) en un array
        // aplicaciones[], una fila por cada una.
        if ($this->isMethod('post')) {
            return [
                'pet_id' => 'nullable|integer|exists:pets,id',
                'reception_id' => 'nullable|integer|exists:receptions,id',
                'vet_id' => 'nullable|integer|exists:users,id',
                'aplicaciones' => 'required|array|min:1',
                'aplicaciones.*.service_id' => 'nullable|integer|exists:services,id',
                'aplicaciones.*.product' => 'nullable|string',
                'aplicaciones.*.lab' => 'nullable|string',
                'aplicaciones.*.lote' => 'nullable|string',
                'aplicaciones.*.dose' => 'nullable|string',
                'aplicaciones.*.application_date' => 'nullable|date',
                'aplicaciones.*.last_deworming_date' => 'nullable|date',
                'aplicaciones.*.next_application_date' => 'required|date',
                'aplicaciones.*.observations' => 'nullable|string',
            ];
        }

        // update() (PUT/PATCH): edición de un único registro existente, formato plano.
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
            'reception_id' => 'nullable|integer|exists:receptions,id',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
