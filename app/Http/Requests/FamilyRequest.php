<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FamilyRequest extends FormRequest
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
        $family = $this->route('family');
        return [
            'name' => 'required|string',
            'phone' => [
                'required',
                'string',
                Rule::unique('families', 'phone')
                    ->ignore($family),
            ],
            'email' => 'required|email',
            'address' => 'required|string',
            'contact_name' => 'required|string',
            'contact_number' => 'required|string',
            'fam_classification_id' => 'nullable|integer|exists:fam_classifications,id',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'El teléfono ya se encuentra asociado a otra familia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'address' => 'dirección',
            'contact_name' => 'nombre del contacto',
            'contact_number' => 'teléfono del contacto',
            'fam_classification_id' => 'clasificación',
        ];
    }
}
