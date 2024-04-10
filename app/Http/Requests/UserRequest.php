<?php

namespace App\Http\Requests;

/**
 * @OA\Schema(
 *     schema="UserRequest",
 *     required={"name", "email", "password"},
 *
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="O nome do usuário",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="O email do usuário",
 *         example="usuario@teste.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="A senha do usuário",
 *         example="12345678"
 *     )
 * )
 */

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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|unique:users|max:255',
                    'password' => 'required|string|min:8',
                    'is_admin' => 'boolean',
                ];
            case 'PUT':
                return [
                    'name' => 'sometimes|string|max:255',
                    'email' => 'sometimes|string|email|unique:users|max:255',
                    'password' => 'sometimes|string|min:8',
                    'is_admin' => 'sometimes|boolean',
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
            'name.required' => 'Nome é obrigatorio',
            'email.required' => 'Email é obrigatorio',
            'email.email' => 'Email é invalido',
            'email.unique' => 'Email já cadastrado',
            'password.required' => 'Password é obrigatorio',
            'password.min' => 'Password deve ter no minimo 8 caracteres',
            'is_admin.boolean' => 'is_admin deve ser true ou false',
        ];
    }
}
