<?php

namespace App\Http\Requests\Processo;

use App\Enums\ProcessoStatus;
use App\Rules\ValidCNJNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProcessoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('processo'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $processoId = $this->route('processo')->id ?? null;

        return [
            'numero_processo' => [
                'sometimes',
                'required',
                'string',
                'max:25',
                Rule::unique('processos', 'numero_processo')->ignore($processoId),
                new ValidCNJNumber(),
            ],
            'cliente_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:clientes,id',
            ],
            'advogado_id' => [
                'sometimes',
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
                'sometimes',
                'required',
                Rule::enum(ProcessoStatus::class),
            ],
            'data_abertura' => [
                'sometimes',
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
            'numero_processo.unique' => 'Este número de processo já está cadastrado.',
            'cliente_id.exists' => 'Cliente não encontrado.',
            'advogado_id.exists' => 'Advogado não encontrado.',
            'data_abertura.before_or_equal' => 'A data de abertura não pode ser futura.',
        ];
    }
}
