<?php

namespace App\Services;

use App\Enums\ProcessoStatus;
use App\Models\Processo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessoService
{
    public function criarProcesso(array $data): Processo
    {
        try {
            DB::beginTransaction();

            $processo = Processo::create($data);

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

            $processo->update($data);
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

            $result = $processo->delete();

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
        return Processo::where('status', ProcessoStatus::ANDAMENTO->value)->get();
    }

    public function buscarProcessosPorStatus(ProcessoStatus $status): \Illuminate\Database\Eloquent\Collection
    {
        return Processo::where('status', $status->value)->get();
    }

    public function contarProcessosAtivos(): int
    {
        return $this->buscarProcessosAtivos()->count();
    }
}

