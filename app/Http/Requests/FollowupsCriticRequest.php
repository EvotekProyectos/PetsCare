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
            'throwup' => 'nullable|string',
			'throwup_detail' => 'nullable|string',
            'defecate' => 'nullable|string',
			'defecate_detail' => 'nullable|string',
            'orino' => 'nullable|string',
			'orino_detail' => 'nullable|string',
            'eat' => 'nullable|string',
			'eat_detail' => 'nullable|string',
            'infusions' => 'nullable|string',
			'infusions_detail' => 'nullable|string',
            'terapeutic' => 'nullable|string',
			'terapeutic_detail' => 'nullable|string',
            'imaging' => 'nullable|string',
			'imaging_detail' => 'nullable|string',
			'pends' => 'nullable|string',
            'vet_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
