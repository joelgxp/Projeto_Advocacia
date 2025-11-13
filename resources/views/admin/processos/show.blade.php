@extends('layouts.app')

@section('title', 'Detalhes do Processo')
@section('page-title', 'Detalhes do Processo')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Processo #{{ $processo->id }}</h5>
                <div>
                    <a href="{{ route('admin.processos.edit', $processo) }}" class="btn btn-sm btn-modern btn-modern-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('admin.processos.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Número CNJ</p>
                        <p class="mb-0 fw-semibold">{{ $processo->numero_cnj ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Status</p>
                        <p class="mb-0">
                            <span class="badge bg-info rounded-pill">{{ $processo->status ?? 'N/A' }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Cliente</p>
                        <p class="mb-0 fw-semibold">{{ $processo->cliente->nome ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Advogado</p>
                        <p class="mb-0 fw-semibold">{{ $processo->advogado->user->name ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Vara</p>
                        <p class="mb-0 fw-semibold">{{ $processo->vara->nome ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Especialidade</p>
                        <p class="mb-0 fw-semibold">{{ $processo->especialidade->nome ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

