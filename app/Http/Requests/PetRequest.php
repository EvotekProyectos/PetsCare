<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
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
            'family_id' => 'required|integer|exists:families,id',
			'name' => 'required|string',
            'number_chip'=>'nullable|string',
            'picture_id' => 'nullable|integer|exists:files,id',
			// specie/raza (texto libre) se conservan por compatibilidad pero
			// ya no son obligatorios ni la fuente de verdad: species_id/
			// breed_id son los campos oficiales (ver withValidator() abajo
			// para la validación de que breed_id pertenezca a species_id).
			'specie' => 'nullable|string',
			'raza' => 'nullable|string',
            'species_id' => 'nullable|integer|exists:species,id',
            'breed_id' => 'nullable|integer|exists:breeds,id',
            'gender_id' => 'required|integer|exists:genres,id',
            'birthday' => 'nullable|date',
            'reproductive_status_id' => 'required|integer|exists:reproductive_statuses,id',
			'weight' => 'nullable|string',
			'physic_descrip' => 'nullable|string',
			'notes' => 'nullable|string',
            'pet_classification_id' => 'nullable|integer|exists:pet_classifications,id',
            'deceased' => 'required|boolean',

        ];
    }

    /**
     * La pertenencia de breed_id a species_id no se puede expresar con las
     * reglas de rules() solas (dependen de dos campos a la vez): se valida
     * acá para que no dependa únicamente del filtrado del select en el
     * frontend (ver requerimiento de "Validación de consistencia").
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->filled('breed_id') || !$this->filled('species_id')) {
                return;
            }

            $breed = \App\Models\Breed::find($this->input('breed_id'));

            if ($breed && (int) $breed->species_id !== (int) $this->input('species_id')) {
                $validator->errors()->add('breed_id', 'La raza seleccionada no pertenece a la especie indicada.');
            }
        });
    }
}
