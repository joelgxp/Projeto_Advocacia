@extends('layouts.app')

@section('title', 'Detalhes do Cliente')
@section('page-title', 'Detalhes do Cliente')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Cliente: {{ $cliente->nome }}</h5>
                <div>
                    <a href="{{ route('admin.clientes.edit', $cliente) }}" class="btn btn-sm btn-modern btn-modern-primary">
                        <i class="fas fa-edit me-2"></i> Editar
                    </a>
                    <a href="{{ route('admin.clientes.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Voltar
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted">Nome</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->nome }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted">CPF/CNPJ</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->cpf_cnpj }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted">Tipo</p>
                        <p class="mb-0"><span class="badge bg-info">{{ $cliente->tipo_pessoa }}</span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted">E-mail</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->email ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted">Telefone</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->telefone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted">Celular</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->celular ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

