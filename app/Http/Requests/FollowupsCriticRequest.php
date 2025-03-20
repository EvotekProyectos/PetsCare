<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowupsCriticRequest extends FormRequest
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
			'pet_status' => 'nullable|string',
			'preasure' => 'nullable|string',
			'temperature' => 'nullable|string',
			'glycemia' => 'nullable|string',
            'throwup' => 'required|boolean',
			'throwup_detail' => 'nullable|string',
            'defecate' => 'required|boolean',
			'defecate_detail' => 'nullable|string',
            'orino' => 'required|boolean',
			'orino_detail' => 'nullable|string',
            'eat' => 'required|boolean',
			'eat_detail' => 'nullable|string',
            'infusions' => 'required|boolean',
			'infusions_detail' => 'nullable|string',
            'terapeutic' => 'required|boolean',
			'terapeutic_detail' => 'nullable|string',
            'imaging' => 'required|boolean',
			'imaging_detail' => 'nullable|string',
			'pends' => 'nullable|string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
