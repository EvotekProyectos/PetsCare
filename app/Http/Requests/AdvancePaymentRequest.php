<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvancePaymentRequest extends FormRequest
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
			'reference' => 'nullable|string',
			'concept' => 'required|string',
			// Ya no la captura el usuario en el modal de creación (ver
			// AdvancePaymentController::store(), que la asigna con now());
			// se deja nullable (no se elimina) porque edit.blade.php sigue
			// enviándola y update() no debe verse afectado.
			'date' => 'nullable',
			'amount' => 'required|numeric|gt:0',
            'user_id' => 'nullable|integer|exists:users,id',
			'status' => 'nullable|boolean',
        ];
    }
}
