@extends('layouts.app')

@section('title', 'Detalhes do Processo - Administrador')

@section('page-title', 'Detalhes do Processo')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.processos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
        <a href="{{ route('admin.processos.edit', $processo->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informações do Processo</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Número:</dt>
                    <dd class="col-sm-9">{{ $processo->numero_processo ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        @if($processo->status)
                            <span class="badge bg-{{ $processo->status->color() }}">
                                {{ $processo->status->label() }}
                            </span>
                        @else
                            <span class="badge bg-secondary">N/A</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">Cliente:</dt>
                    <dd class="col-sm-9">{{ $processo->cliente->nome ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Advogado:</dt>
                    <dd class="col-sm-9">{{ $processo->advogado->user->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Vara:</dt>
                    <dd class="col-sm-9">{{ $processo->vara->nome ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Especialidade:</dt>
                    <dd class="col-sm-9">{{ $processo->especialidade->nome ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Data de Abertura:</dt>
                    <dd class="col-sm-9">{{ $processo->data_abertura?->format('d/m/Y') ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Data da Petição:</dt>
                    <dd class="col-sm-9">{{ $processo->data_peticao?->format('d/m/Y') ?? 'N/A' }}</dd>

                    @if($processo->observacoes)
                        <dt class="col-sm-3">Observações:</dt>
                        <dd class="col-sm-9">{{ $processo->observacoes }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas</h5>
            </div>
            <div class="card-body">
                <p><strong>Documentos:</strong> {{ $processo->documentos->count() }}</p>
                <p><strong>Audiências:</strong> {{ $processo->audiencias->count() }}</p>
                <p><strong>Prazos:</strong> {{ $processo->prazos->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection



