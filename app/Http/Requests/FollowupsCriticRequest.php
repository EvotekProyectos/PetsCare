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
            'throwup' => 'string',
			'throwup_detail' => 'string',
            'defecate' => 'string',
			'defecate_detail' => 'string',
            'orino' => 'string',
			'orino_detail' => 'string',
            'eat' => 'string',
			'eat_detail' => 'string',
            'infusions' => 'string',
			'infusions_detail' => 'string',
            'terapeutic' => 'string',
			'terapeutic_detail' => 'string',
            'imaging' => 'string',
			'imaging_detail' => 'string',
			'pends' => 'string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
