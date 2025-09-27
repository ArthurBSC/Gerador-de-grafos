<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalcularCaminhoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return session('user_logged_in', false);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'origem' => 'required|integer|min:1',
            'destino' => 'required|integer|min:1|different:origem'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'origem.required' => 'O nó de origem é obrigatório.',
            'origem.integer' => 'O nó de origem deve ser um número inteiro.',
            'origem.min' => 'O nó de origem deve ser pelo menos 1.',
            'destino.required' => 'O nó de destino é obrigatório.',
            'destino.integer' => 'O nó de destino deve ser um número inteiro.',
            'destino.min' => 'O nó de destino deve ser pelo menos 1.',
            'destino.different' => 'O nó de destino deve ser diferente do nó de origem.'
        ];
    }
}
