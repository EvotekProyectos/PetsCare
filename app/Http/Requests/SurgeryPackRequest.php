<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurgeryPackRequest extends FormRequest
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
			'total' => 'required|string',
			'catheterization_price' => 'required|string',
			'preanesthetic_price' => 'required|string',
			'monitoring_price' => 'required|string',
			'surgical_clothing_price' => 'required|string',
			'preparations_price' => 'required|string',
			'observation_price' => 'required|string',
        ];
    }
}
