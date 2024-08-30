<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
			'name' => 'required|string',
			'phone' => 'required|string',
			'email' => 'required|string',
			'address' => 'required|string',
			'contact_name' => 'required|string',
			'contact_number' => 'required|string',
            'fam_classification_id' => 'required|integer|exists:fam_classifications,id',
        ];
    }
}
