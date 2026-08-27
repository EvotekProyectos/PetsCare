<?php

namespace App\Http\Requests;

use App\Models\Reception;
use Illuminate\Foundation\Http\FormRequest;

class ReceptionTransferRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'reception_type_id' => [
                'required',
                'integer',
                'exists:reception_types,id',
                function ($attribute, $value, $fail) {
                    $origin = Reception::find($this->route('id'));
                    if ($origin && (int) $value === (int) $origin->reception_type_id) {
                        $fail('El tipo de recepción destino debe ser distinto al de la recepción actual.');
                    }
                },
            ],
            'reason' => 'required|string|max:500',
            'admission_type_id' => 'required_if:reception_type_id,2|nullable|integer|exists:admission_types,id',
            'area_id' => 'required_if:reception_type_id,2|nullable|integer|exists:areas,id',
            'reason_id' => 'required_if:reception_type_id,1|nullable|integer|exists:reasons,id',
            'anamnesis' => 'nullable|string',
            'exam_details' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'observations' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'reception_type_id' => 'tipo de recepción destino',
            'reason' => 'motivo',
            'admission_type_id' => 'admisión',
            'area_id' => 'área',
            'reason_id' => 'motivo de la consulta',
            'anamnesis' => 'anamnesis',
            'exam_details' => 'detalles del examen',
            'diagnosis' => 'diagnóstico',
            'observations' => 'observaciones',
        ];
    }
}
