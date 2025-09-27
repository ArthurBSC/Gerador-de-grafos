<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrafoRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'tipo' => 'required|in:direcionado,nao_direcionado',
            'quantidade_nos' => 'required|integer|min:2|max:26',
            'modo_pesos' => 'required|in:automatico,especifico',
            'conexoes_origem' => 'array|required_if:modo_pesos,especifico',
            'conexoes_destino' => 'array|required_if:modo_pesos,especifico',
            'conexoes_peso' => 'array|required_if:modo_pesos,especifico',
            'conexoes_origem.*' => 'integer|min:0|max:25',
            'conexoes_destino.*' => 'integer|min:0|max:25',
            'conexoes_peso.*' => 'integer|min:-100|max:100'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do grafo é obrigatório.',
            'nome.max' => 'O nome do grafo não pode ter mais de 255 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'tipo.required' => 'O tipo do grafo é obrigatório.',
            'tipo.in' => 'O tipo do grafo deve ser direcionado ou não direcionado.',
            'quantidade_nos.required' => 'A quantidade de nós é obrigatória.',
            'quantidade_nos.min' => 'O grafo deve ter pelo menos 2 nós.',
            'quantidade_nos.max' => 'O grafo não pode ter mais de 26 nós (A-Z).',
            'modo_pesos.required' => 'O modo de pesos é obrigatório.',
            'modo_pesos.in' => 'O modo de pesos deve ser automático ou específico.',
            'conexoes_origem.required_if' => 'As conexões de origem são obrigatórias quando o modo é específico.',
            'conexoes_destino.required_if' => 'As conexões de destino são obrigatórias quando o modo é específico.',
            'conexoes_peso.required_if' => 'Os pesos das conexões são obrigatórios quando o modo é específico.',
            'conexoes_origem.*.integer' => 'Cada origem deve ser um número inteiro.',
            'conexoes_origem.*.min' => 'Cada origem deve ser pelo menos 0.',
            'conexoes_origem.*.max' => 'Cada origem não pode ser maior que 25 (Z).',
            'conexoes_destino.*.integer' => 'Cada destino deve ser um número inteiro.',
            'conexoes_destino.*.min' => 'Cada destino deve ser pelo menos 0.',
            'conexoes_destino.*.max' => 'Cada destino não pode ser maior que 25 (Z).',
            'conexoes_peso.*.integer' => 'Cada peso deve ser um número inteiro.',
            'conexoes_peso.*.min' => 'Cada peso deve ser pelo menos -100.',
            'conexoes_peso.*.max' => 'Cada peso não pode ser maior que 100.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Garantir que arrays de conexões tenham o mesmo tamanho
        if ($this->has('conexoes_origem') && $this->has('conexoes_destino') && $this->has('conexoes_peso')) {
            $origens = $this->input('conexoes_origem', []);
            $destinos = $this->input('conexoes_destino', []);
            $pesos = $this->input('conexoes_peso', []);
            
            $tamanhoMinimo = min(count($origens), count($destinos), count($pesos));
            
            if ($tamanhoMinimo > 0) {
                $this->merge([
                    'conexoes_origem' => array_slice($origens, 0, $tamanhoMinimo),
                    'conexoes_destino' => array_slice($destinos, 0, $tamanhoMinimo),
                    'conexoes_peso' => array_slice($pesos, 0, $tamanhoMinimo)
                ]);
            }
        }
    }
}
