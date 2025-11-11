@extends('layouts.app')

@section('title', 'Processos - Administrador')

@section('page-title', 'Processos')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.processos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Processo
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lista de Processos</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($processos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>Advogado</th>
                            <th>Vara</th>
                            <th>Status</th>
                            <th>Data Abertura</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processos as $processo)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.processos.show', $processo->id) }}">
                                        {{ $processo->numero_processo ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $processo->cliente->nome ?? 'N/A' }}</td>
                                <td>{{ $processo->advogado->user->name ?? 'N/A' }}</td>
                                <td>{{ $processo->vara->nome ?? 'N/A' }}</td>
                                <td>
                                    @if($processo->status)
                                        <span class="badge bg-{{ $processo->status->color() }}">
                                            {{ $processo->status->label() }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $processo->data_abertura?->format('d/m/Y') ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.processos.show', $processo->id) }}" 
                                           class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.processos.edit', $processo->id) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.processos.destroy', $processo->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este processo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $processos->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Nenhum processo encontrado.
            </div>
        @endif
    </div>
</div>
@endsection


