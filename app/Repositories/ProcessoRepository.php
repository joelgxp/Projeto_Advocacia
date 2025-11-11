<?php

namespace App\Repositories;

use App\Models\Processo;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProcessoRepository implements ProcessoRepositoryInterface
{
    public function all(): Collection
    {
        return Processo::with(['cliente', 'advogado', 'vara', 'especialidade'])->get();
    }

    public function find(int $id): ?Processo
    {
        return Processo::with(['cliente', 'advogado', 'vara', 'especialidade'])->find($id);
    }

    public function findByNumero(string $numero): ?Processo
    {
        return Processo::where('numero_processo', $numero)
            ->with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->first();
    }

    public function create(array $data): Processo
    {
        return Processo::create($data);
    }

    public function update(Processo $processo, array $data): bool
    {
        return $processo->update($data);
    }

    public function delete(Processo $processo): bool
    {
        return $processo->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Processo::with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findByStatus(string $status): Collection
    {
        return Processo::where('status', $status)
            ->with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->get();
    }

    public function findByCliente(int $clienteId): Collection
    {
        return Processo::where('cliente_id', $clienteId)
            ->with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->get();
    }

    public function findByAdvogado(int $advogadoId): Collection
    {
        return Processo::where('advogado_id', $advogadoId)
            ->with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->get();
    }

    public function search(string $term): Collection
    {
        return Processo::where('numero_processo', 'like', "%{$term}%")
            ->orWhereHas('cliente', function ($query) use ($term) {
                $query->where('nome', 'like', "%{$term}%");
            })
            ->with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->get();
    }
}


