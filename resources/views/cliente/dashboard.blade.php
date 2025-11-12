@extends('layouts.app')

@section('title', 'Dashboard - Cliente')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard - Cliente</h1>
</div>

@if($cliente)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações do Cliente</h5>
            </div>
            <div class="card-body">
                <p><strong>Nome:</strong> {{ $cliente->nome }}</p>
                <p><strong>CPF/CNPJ:</strong> {{ $cliente->cpf_cnpj }}</p>
                @if($cliente->email)
                <p><strong>E-mail:</strong> {{ $cliente->email }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Meus Processos</h5>
                <span class="badge bg-primary">{{ $processos->count() ?? 0 }} processo(s)</span>
            </div>
            <div class="card-body">
                @if($processos && $processos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                    <span class="badge bg-info">{{ $processo->status ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('cliente.processos.show', $processo->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted">Nenhum processo encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

