<?php

namespace App\Services;

use App\Enums\ProcessoStatus;
use App\Models\Processo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ProcessoService
{
    public function criarProcesso(array $data): Processo
    {
        try {
            DB::beginTransaction();

            // Validar duplicidade
            if ($this->processoExiste($data['numero_processo'])) {
                throw new InvalidArgumentException('Processo com este número já existe.');
            }

            // Validar se cliente e advogado existem
            $this->validarRelacionamentos($data);

            $processo = Processo::create($data);

            // Log da criação com contexto
            Log::info('Processo criado', [
                'processo_id' => $processo->id,
                'numero' => $processo->numero_processo,
                'cliente_id' => $processo->cliente_id,
                'advogado_id' => $processo->advogado_id,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return $processo;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar processo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'user_id' => auth()->id(),
            ]);
            throw $e;
        }
    }

    /**
     * Verifica se processo já existe
     */
    private function processoExiste(string $numeroProcesso): bool
    {
        return Processo::where('numero_processo', $numeroProcesso)->exists();
    }

    /**
     * Valida relacionamentos necessários
     */
    private function validarRelacionamentos(array $data): void
    {
        if (isset($data['cliente_id']) && !\App\Models\Cliente::find($data['cliente_id'])) {
            throw new InvalidArgumentException('Cliente não encontrado.');
        }

        if (isset($data['advogado_id']) && !\App\Models\Advogado::find($data['advogado_id'])) {
            throw new InvalidArgumentException('Advogado não encontrado.');
        }

        if (isset($data['vara_id']) && !\App\Models\Vara::find($data['vara_id'])) {
            throw new InvalidArgumentException('Vara não encontrada.');
        }
    }

    public function atualizarProcesso(Processo $processo, array $data): Processo
    {
        try {
            DB::beginTransaction();

            // Validar relacionamentos se fornecidos
            $this->validarRelacionamentos($data);

            // Armazenar valores antigos para auditoria
            $valoresAntigos = $processo->only(array_keys($data));

            $processo->update($data);
            $processo->refresh();

            Log::info('Processo atualizado', [
                'processo_id' => $processo->id,
                'valores_antigos' => $valoresAntigos,
                'valores_novos' => $processo->only(array_keys($data)),
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return $processo;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar processo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'processo_id' => $processo->id,
                'user_id' => auth()->id(),
            ]);
            throw $e;
        }
    }

    public function alterarStatus(Processo $processo, ProcessoStatus $status, ?string $motivo = null): bool
    {
        $statusAntigo = $processo->status;
        
        $result = $this->atualizarProcesso($processo, [
            'status' => $status->value,
        ]) !== null;

        if ($result) {
            Log::info('Status do processo alterado', [
                'processo_id' => $processo->id,
                'status_antigo' => $statusAntigo?->value,
                'status_novo' => $status->value,
                'motivo' => $motivo,
                'user_id' => auth()->id(),
            ]);
        }

        return $result;
    }

    public function excluirProcesso(Processo $processo): bool
    {
        try {
            DB::beginTransaction();

            // Verificar se há dependências críticas
            if ($processo->documentos()->count() > 0 || $processo->audiencias()->count() > 0) {
                Log::warning('Tentativa de excluir processo com dependências', [
                    'processo_id' => $processo->id,
                    'documentos' => $processo->documentos()->count(),
                    'audiencias' => $processo->audiencias()->count(),
                    'user_id' => auth()->id(),
                ]);
            }

            $result = $processo->delete();

            Log::info('Processo excluído', [
                'processo_id' => $processo->id,
                'numero_processo' => $processo->numero_processo,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir processo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'processo_id' => $processo->id,
                'user_id' => auth()->id(),
            ]);
            throw $e;
        }
    }

    public function buscarProcessosAtivos(): \Illuminate\Database\Eloquent\Collection
    {
        return Processo::ativos()
            ->withRelations()
            ->orderBy('data_abertura', 'desc')
            ->get();
    }

    public function buscarProcessosPorStatus(ProcessoStatus $status): \Illuminate\Database\Eloquent\Collection
    {
        return Processo::where('status', $status->value)
            ->withRelations()
            ->orderBy('data_abertura', 'desc')
            ->get();
    }

    public function contarProcessosAtivos(): int
    {
        return Processo::ativos()->count();
    }

    /**
     * Busca processos com prazos vencendo
     */
    public function buscarProcessosComPrazosVencendo(int $dias = 7): \Illuminate\Database\Eloquent\Collection
    {
        return Processo::comPrazosVencendo($dias)
            ->withRelations()
            ->with(['prazos' => function ($query) use ($dias) {
                $query->where('data_vencimento', '<=', now()->addDays($dias))
                      ->where('data_vencimento', '>=', now())
                      ->whereNull('data_cumprimento');
            }])
            ->get();
    }
}

