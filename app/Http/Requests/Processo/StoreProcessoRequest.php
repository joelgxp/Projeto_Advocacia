<?php

namespace App\Http\Requests\Processo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProcessoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Processo::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'numero_processo' => ['required', 'string', 'max:50', 'unique:processos,numero_processo'],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'advogado_id' => ['required', 'exists:advogados,id'],
            'vara_id' => ['required', 'exists:varas,id'],
            'especialidade_id' => ['required', 'exists:especialidades,id'],
            'status' => ['required', Rule::in(['aberto', 'andamento', 'concluido', 'arquivado', 'cancelado'])],
            'data_abertura' => ['required', 'date'],
            'data_peticao' => ['nullable', 'date'],
            'data_audiencia' => ['nullable', 'date'],
            'hora_audiencia' => ['nullable', 'date_format:H:i'],
            'observacoes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'numero_processo.required' => 'O número do processo é obrigatório.',
            'numero_processo.unique' => 'Este número de processo já está cadastrado.',
            'cliente_id.required' => 'O cliente é obrigatório.',
            'cliente_id.exists' => 'O cliente selecionado não existe.',
            'advogado_id.required' => 'O advogado é obrigatório.',
            'advogado_id.exists' => 'O advogado selecionado não existe.',
        ];
    }
}



