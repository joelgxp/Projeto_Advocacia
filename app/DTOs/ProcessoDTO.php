<?php

namespace App\DTOs;

use App\Enums\ProcessoStatus;
use Carbon\Carbon;

/**
 * Data Transfer Object para Processo
 */
class ProcessoDTO
{
    public function __construct(
        public readonly string $numeroProcesso,
        public readonly int $clienteId,
        public readonly int $advogadoId,
        public readonly ProcessoStatus $status,
        public readonly Carbon $dataAbertura,
        public readonly ?int $varaId = null,
        public readonly ?int $especialidadeId = null,
        public readonly ?int $processadoId = null,
        public readonly ?Carbon $dataPeticao = null,
        public readonly ?Carbon $dataAudiencia = null,
        public readonly ?string $horaAudiencia = null,
        public readonly ?string $observacoes = null,
    ) {}

    /**
     * Cria DTO a partir de array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            numeroProcesso: $data['numero_processo'],
            clienteId: $data['cliente_id'],
            advogadoId: $data['advogado_id'],
            status: $data['status'] instanceof ProcessoStatus 
                ? $data['status'] 
                : ProcessoStatus::from($data['status']),
            dataAbertura: $data['data_abertura'] instanceof Carbon 
                ? $data['data_abertura'] 
                : Carbon::parse($data['data_abertura']),
            varaId: $data['vara_id'] ?? null,
            especialidadeId: $data['especialidade_id'] ?? null,
            processadoId: $data['processado_id'] ?? null,
            dataPeticao: isset($data['data_peticao']) 
                ? ($data['data_peticao'] instanceof Carbon ? $data['data_peticao'] : Carbon::parse($data['data_peticao']))
                : null,
            dataAudiencia: isset($data['data_audiencia']) 
                ? ($data['data_audiencia'] instanceof Carbon ? $data['data_audiencia'] : Carbon::parse($data['data_audiencia']))
                : null,
            horaAudiencia: $data['hora_audiencia'] ?? null,
            observacoes: $data['observacoes'] ?? null,
        );
    }

    /**
     * Converte DTO para array
     */
    public function toArray(): array
    {
        return [
            'numero_processo' => $this->numeroProcesso,
            'cliente_id' => $this->clienteId,
            'advogado_id' => $this->advogadoId,
            'status' => $this->status->value,
            'data_abertura' => $this->dataAbertura->format('Y-m-d'),
            'vara_id' => $this->varaId,
            'especialidade_id' => $this->especialidadeId,
            'processado_id' => $this->processadoId,
            'data_peticao' => $this->dataPeticao?->format('Y-m-d'),
            'data_audiencia' => $this->dataAudiencia?->format('Y-m-d'),
            'hora_audiencia' => $this->horaAudiencia,
            'observacoes' => $this->observacoes,
        ];
    }
}
