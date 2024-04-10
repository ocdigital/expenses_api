<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'number' => 'required|unique:cards|string|max:255',
                    'balance' => 'required|numeric|min:0',
                    'user_id' => 'required|string|exists:users,id',
                ];
            case 'PUT':
                return [
                    'number' => 'sometimes|unique:cards|string|max:255',
                    'balance' => 'sometimes|numeric|min:0',
                    'user_id' => 'sometimes|string|exists:users,id',
                ];
            default:
                return [];
        }

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'number.required' => 'Número do Carão é obrigatório',
            'number.unique' => 'Número do Cartão já cadastrado',
            'balance.required' => 'Saldo é obrigatório',
            'balance.numeric' => 'Saldo deve ser um número',
            'balance.min' => 'Saldo deve ser maior ou igual a 0',
            'user_id.required' => 'Usuário é obrigatório',
            'user_id.integer' => 'Usuário deve ser um número',
            'user_id.exists' => 'Usuário não encontrado',
        ];
    }
}
