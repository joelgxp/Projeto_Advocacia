@extends('layouts.app')

@section('title', 'Advogados - Administrador')

@section('page-title', 'Advogados')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.advogados.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Advogado
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lista de Advogados</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($advogados->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>OAB</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advogados as $advogado)
                            <tr>
                                <td>{{ $advogado->user->name ?? 'N/A' }}</td>
                                <td>{{ $advogado->oab ?? 'N/A' }}</td>
                                <td>{{ $advogado->user->email ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $advogado->ativo ? 'success' : 'secondary' }}">
                                        {{ $advogado->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.advogados.show', $advogado->id) }}" 
                                           class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.advogados.edit', $advogado->id) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $advogados->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Nenhum advogado encontrado.
            </div>
        @endif
    </div>
</div>
@endsection