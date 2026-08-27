<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedSheetRequest extends FormRequest
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
            'lab_type_id' => 'nullable|array',
            'lab_type_id.*' => 'integer',
            'imaging_type_id' => 'nullable|array',
            'imaging_type_id.*' => 'integer',
            'service_type_id' => 'nullable|array',
            'service_type_id.*' => 'integer',
			'day_count' => 'required',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }

    /**
     * service_type_id/lab_type_id/imaging_type_id son 3 selects independientes
     * (ver red-sheet.form): cualquiera puede venir vacío por separado, pero no
     * los 3 a la vez, o se estaría pidiendo crear un lote sin nada que registrar.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasSelection = collect(['service_type_id', 'lab_type_id', 'imaging_type_id'])
                ->contains(fn ($field) => !empty($this->input($field, [])));

            if (!$hasSelection) {
                $validator->errors()->add(
                    'service_type_id',
                    'Selecciona al menos un servicio, laboratorio o estudio de imagenología.'
                );
            }
        });
    }
}
