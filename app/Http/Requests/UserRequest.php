<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_name' => 'required'
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) { // Para editar
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->route('user')->id;
            $rules['password'] = 'nullable|string|min:8|confirmed'; // Permitir campos opcionales
        }

        return $rules;
    }
}
