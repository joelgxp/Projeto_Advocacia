@extends('layouts.app')

@section('title', 'Dashboard - Cliente')
@section('page-title', 'Dashboard - Cliente')

@section('content')
@if($cliente)
<div class="row mb-4 fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Informações do Cliente</h5>
            </div>
            <div class="modern-card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <p class="mb-1 text-muted">Nome</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->nome }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <p class="mb-1 text-muted">CPF/CNPJ</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->cpf_cnpj }}</p>
                    </div>
                    @if($cliente->email)
                    <div class="col-md-4 mb-3">
                        <p class="mb-1 text-muted">E-mail</p>
                        <p class="mb-0 fw-semibold">{{ $cliente->email }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mb-4 fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Meus Processos</h5>
                <span class="badge bg-primary rounded-pill px-3 py-2">{{ $processos->count() ?? 0 }} processo(s)</span>
            </div>
            <div class="modern-card-body">
                @if($processos && $processos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Número CNJ</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($processos as $processo)
                            <tr>
                                <td>{{ $processo->numero_cnj ?? 'N/A' }}</td>
                                <td>{{ $processo->cliente->nome ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info rounded-pill">{{ $processo->status ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('cliente.processos.show', $processo->id) }}" class="btn btn-sm btn-modern btn-modern-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4">Nenhum processo encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

