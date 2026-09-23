<?php

namespace App\Http\Requests;

use App\Rules\ValidPhoneNumber;
use App\Services\Phone\PhoneNumberService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use libphonenumber\NumberParseException;

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
     * Normaliza los teléfonos a E.164 antes de que corran las reglas de validación,
     * para que Rule::unique() (en "phone") siempre compare contra el formato canónico.
     * Si un número no puede normalizarse, se deja el valor original tal cual llegó,
     * para que ValidPhoneNumber lo rechace con un mensaje claro.
     */
    protected function prepareForValidation(): void
    {
        $service = app(PhoneNumberService::class);

        $this->merge([
            'phone' => $this->normalizePhone($this->input('phone'), $service),
            'contact_number' => $this->normalizePhone($this->input('contact_number'), $service),
        ]);
    }

    private function normalizePhone(?string $raw, PhoneNumberService $service): ?string
    {
        if (blank($raw)) {
            return $raw;
        }

        try {
            return $service->toE164($raw);
        } catch (NumberParseException) {
            return $raw;
        }
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
                new ValidPhoneNumber(),
                Rule::unique('families', 'phone')
                    ->ignore($family),
            ],
            'email' => 'required|email',
            'email_confirmation' => [
                'required',
                'same:email',
            ],
            'address' => 'required|string',
            'contact_name' => 'required|string',
            'contact_number' => [
                'required',
                'string',
                new ValidPhoneNumber(),
            ],
            'fam_classification_id' => 'nullable|integer|exists:fam_classifications,id',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'El teléfono ya se encuentra asociado a otra familia.',
            'email_confirmation.required' => 'Debes confirmar el correo electrónico.',
            'email_confirmation.same' => 'La confirmación del correo electrónico no coincide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'email_confirmation' => 'confirmación de correo electrónico',
            'address' => 'dirección',
            'contact_name' => 'nombre del contacto',
            'contact_number' => 'teléfono del contacto',
            'fam_classification_id' => 'clasificación',
        ];
    }
}
