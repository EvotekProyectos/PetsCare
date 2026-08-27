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
            // 'vaccine_certificate_id' => 'nullable|integer|exists:vaccine_certificates,id',
			'food' => 'required|string',
			'objects' => 'required|string',
			'observations' => 'required|string',
			'number_days' => 'nullable',
            'extension' => 'nullable',
            'service_type_id' => 'required',
            'finish_date' => 'nullable',
            'video' => 'nullable',
            'status' => 'nullable',
            'folio' => 'nullable|integer',
            'cubicle_id' => 'nullable|integer|exists:cubicles,id',
            
        ];
    }

    public function messages(): array
{
    return [
        'food.required' => 'El campo tipo de alimentación es obligatorio.',
        'objects.required' => 'El campo tipo de objetos es obligatorio.',
        'observations.required' => 'El campo observaciones es obligatorio.',
        'service_type_id.required' => 'El tipo de servicio es obligatorio.',

        'reception_id.integer' => 'La recepción seleccionada no es válida.',
        'reception_id.exists' => 'La recepción seleccionada no existe.',

        'folio.integer' => 'El folio debe ser un número entero.',
        'cubicle_id.integer' => 'El cubículo seleccionado no es válido.',
        'cubicle_id.exists' => 'El cubículo seleccionado no existe.',
    ];
}
}
