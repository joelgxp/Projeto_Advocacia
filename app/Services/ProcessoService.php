<?php

namespace App\Services;

use App\Enums\ProcessoStatus;
use App\Models\Processo;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessoService
{
    public function __construct(
        private ProcessoRepositoryInterface $processoRepository
    ) {}

    public function criarProcesso(array $data): Processo
    {
        try {
            DB::beginTransaction();

            $processo = $this->processoRepository->create($data);

            // Log da criação
            Log::info('Processo criado', [
                'processo_id' => $processo->id,
                'numero' => $processo->numero_processo,
            ]);

            DB::commit();

            return $processo;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar processo', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    public function atualizarProcesso(Processo $processo, array $data): Processo
    {
        try {
            DB::beginTransaction();

            $this->processoRepository->update($processo, $data);
            $processo->refresh();

            Log::info('Processo atualizado', [
                'processo_id' => $processo->id,
            ]);

            DB::commit();

            return $processo;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar processo', [
                'error' => $e->getMessage(),
                'processo_id' => $processo->id,
            ]);
            throw $e;
        }
    }

    public function alterarStatus(Processo $processo, ProcessoStatus $status): bool
    {
        return $this->atualizarProcesso($processo, [
            'status' => $status->value,
        ]) !== null;
    }

    public function excluirProcesso(Processo $processo): bool
    {
        try {
            DB::beginTransaction();

            $result = $this->processoRepository->delete($processo);

            Log::info('Processo excluído', [
                'processo_id' => $processo->id,
            ]);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir processo', [
                'error' => $e->getMessage(),
                'processo_id' => $processo->id,
            ]);
            throw $e;
        }
    }

    public function buscarProcessosAtivos(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->processoRepository->findByStatus(ProcessoStatus::ANDAMENTO->value);
    }

    public function buscarProcessosPorStatus(ProcessoStatus $status): \Illuminate\Database\Eloquent\Collection
    {
        return $this->processoRepository->findByStatus($status->value);
    }

    public function contarProcessosAtivos(): int
    {
        return $this->buscarProcessosAtivos()->count();
    }
}

