<?php

namespace App\Http\Requests\Processo;

use App\Enums\ProcessoStatus;
use App\Rules\ValidCNJNumber;
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
            'numero_processo' => [
                'required',
                'string',
                'max:25',
                'unique:processos,numero_processo',
                new ValidCNJNumber(),
            ],
            'cliente_id' => [
                'required',
                'integer',
                'exists:clientes,id',
            ],
            'advogado_id' => [
                'required',
                'integer',
                'exists:advogados,id',
            ],
            'vara_id' => [
                'nullable',
                'integer',
                'exists:varas,id',
            ],
            'especialidade_id' => [
                'nullable',
                'integer',
                'exists:especialidades,id',
            ],
            'processado_id' => [
                'nullable',
                'integer',
                'exists:clientes,id',
            ],
            'status' => [
                'required',
                Rule::enum(ProcessoStatus::class),
            ],
            'data_abertura' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'data_peticao' => [
                'nullable',
                'date',
            ],
            'data_audiencia' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'hora_audiencia' => [
                'nullable',
                'date_format:H:i',
            ],
            'observacoes' => [
                'nullable',
                'string',
                'max:5000',
            ],
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
            'cliente_id.exists' => 'Cliente não encontrado.',
            'advogado_id.required' => 'O advogado responsável é obrigatório.',
            'advogado_id.exists' => 'Advogado não encontrado.',
            'data_abertura.required' => 'A data de abertura é obrigatória.',
            'data_abertura.before_or_equal' => 'A data de abertura não pode ser futura.',
            'data_audiencia.after_or_equal' => 'A data da audiência não pode ser no passado.',
        ];
    }
}
