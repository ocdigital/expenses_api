<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ExpenseRequest",
 *     required={"number", "amount", "description"},
 *     @OA\Property(
 *         property="number",
 *         type="integer",
 *         description="O número do cartão",
 *         example="1234567890123456"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         description="O valor da despesa",
 *         example="100.00"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="A descrição da despesa",
 *         example="Compra de material de escritório"
 *     )
 * )
 */

class ExpenseRequest extends FormRequest
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
                    'number' => 'required|integer|exists:cards,number',
                    'amount' => 'required|numeric|min:0',
                    'description' => 'required|string|max:255',
                ];
            case 'PUT':
                return [
                    'amount' => 'sometimes|numeric|min:0',
                    'description' => 'sometimes|string|max:255',
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
            'number.required' => 'Cartão é obrigatório',
            'number.integer' => 'Cartão deve ser um número',
            'number.exists' => 'Cartão não encontrado',
            'amount.required' => 'Valor é obrigatório',
            'amount.numeric' => 'Valor deve ser um número',
            'amount.min' => 'Valor deve ser maior ou igual a 0',
            'description.required' => 'Descrição é obrigatória',
        ];
    }
}
