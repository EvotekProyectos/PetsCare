<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
			'folio' => 'required|string',
			'status' => 'required',
			'reception_id' => 'required',
			'vet_id' => 'required',
			'generated_document' => 'string',
			'warehouse_observations' => 'string',
			'cancellation_reason' => 'string',
			'rejection_reason' => 'string',
        ];
    }
}
