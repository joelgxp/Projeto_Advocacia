@extends('layouts.app')

@section('title', 'Detalhes do Advogado - Administrador')

@section('page-title', 'Detalhes do Advogado')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.advogados.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
        <a href="{{ route('admin.advogados.edit', $advogado->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Informações do Advogado</h5>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Nome:</dt>
            <dd class="col-sm-9">{{ $advogado->user->name ?? 'N/A' }}</dd>

            <dt class="col-sm-3">OAB:</dt>
            <dd class="col-sm-9">{{ $advogado->oab ?? 'N/A' }}</dd>

            <dt class="col-sm-3">Email:</dt>
            <dd class="col-sm-9">{{ $advogado->user->email ?? 'N/A' }}</dd>

            <dt class="col-sm-3">CPF:</dt>
            <dd class="col-sm-9">{{ $advogado->user->cpf ?? 'N/A' }}</dd>

            <dt class="col-sm-3">Status:</dt>
            <dd class="col-sm-9">
                <span class="badge bg-{{ $advogado->ativo ? 'success' : 'secondary' }}">
                    {{ $advogado->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </dd>
        </dl>
    </div>
</div>
@endsection