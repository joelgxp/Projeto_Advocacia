<?php

namespace App\Repositories\Contracts;

use App\Models\Processo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProcessoRepositoryInterface
{
    public function all(): Collection;
    
    public function find(int $id): ?Processo;
    
    public function findByNumero(string $numero): ?Processo;
    
    public function create(array $data): Processo;
    
    public function update(Processo $processo, array $data): bool;
    
    public function delete(Processo $processo): bool;
    
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    
    public function findByStatus(string $status): Collection;
    
    public function findByCliente(int $clienteId): Collection;
    
    public function findByAdvogado(int $advogadoId): Collection;
    
    public function search(string $term): Collection;
}

