<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReceptionRequest extends FormRequest
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
     * Campos obligatorios por tipo de recepción (ver togglee() en
     * public/js/receptions/modal.js, que ya muestra/oculta estos mismos
     * bloques según el tipo elegido): 1 Consulta (reason_id, veterinarian_id),
     * 2 Hospital (admission_type_id, area_id, veterinarian_id), 3 Grooming
     * (veterinarian_id reusado como "colaborador", num como "collar"),
     * 4 Hotel (exit_date, num), 5 Cremación (solo los generales).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = (int) $this->input('reception_type_id');

        // "No fechas pasadas" solo aplica al CREAR: al editar, una recepción
        // real casi siempre tiene entry_date en el pasado (fue creada antes).
        $isUpdate = $this->isMethod('PATCH') || $this->isMethod('PUT');
        $entryDateRules = ['required', 'date'];
        if (!$isUpdate) {
            $entryDateRules[] = 'after_or_equal:today';
        }

        return [

            'pet_id' => 'required|integer|exists:pets,id',
            'reception_type_id' => 'required|integer|exists:reception_types,id',
            'admission_type_id' => [Rule::requiredIf($type === 2), 'nullable', 'integer', 'exists:admission_types,id'],
            'area_id' => [Rule::requiredIf($type === 2), 'nullable', 'integer', 'exists:areas,id'],
            'family_id' => 'required|integer|exists:families,id',
            'reason_id' => [Rule::requiredIf($type === 1), 'nullable', 'integer', 'exists:reasons,id'],
            'veterinarian_id' => [Rule::requiredIf(in_array($type, [1, 2, 3])), 'nullable', 'integer', 'exists:users,id'],
            'recepcionist_id' => 'nullable|integer|exists:users,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'entry_date' => $entryDateRules,
            // after_or_equal:entry_date aplica siempre (crear y editar): es
            // consistencia de datos, no la restricción de "no pasado" de
            // arriba, que solo va en entry_date.
            'exit_date' => [Rule::requiredIf($type === 4), 'nullable', 'date', 'after_or_equal:entry_date'],
            'num' => [Rule::requiredIf(in_array($type, [3, 4])), 'nullable', 'integer'],

        ];
    }

    public function attributes(): array
    {
        return [
            'pet_id' => 'mascota',
            'veterinarian_id' => 'M.V.Z.',
            'reception_type_id' => 'tipo de recepción',
            'entry_date' => 'fecha de ingreso',
            'family_id' => 'familia',
            'reason_id' => 'motivo',
            'room_id' => 'consultorio',
        ];
    }
}
