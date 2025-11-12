@extends('layouts.app')

@section('title', 'Detalhes do Cliente - Administrador')

@section('page-title', 'Detalhes do Cliente')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
        <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informações do Cliente</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nome:</dt>
                    <dd class="col-sm-9">{{ $cliente->nome }}</dd>

                    <dt class="col-sm-3">CPF/CNPJ:</dt>
                    <dd class="col-sm-9">{{ $cliente->cpf_cnpj }}</dd>

                    <dt class="col-sm-3">Tipo:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $cliente->tipo_pessoa == 'PF' ? 'info' : 'primary' }}">
                            {{ $cliente->tipo_pessoa }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9">{{ $cliente->email ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Telefone:</dt>
                    <dd class="col-sm-9">{{ $cliente->telefone ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Celular:</dt>
                    <dd class="col-sm-9">{{ $cliente->celular ?? 'N/A' }}</dd>

                    @if($cliente->endereco)
                        <dt class="col-sm-3">Endereço:</dt>
                        <dd class="col-sm-9">{{ $cliente->endereco }}, {{ $cliente->numero ?? '' }}</dd>
                    @endif

                    @if($cliente->cidade)
                        <dt class="col-sm-3">Cidade/Estado:</dt>
                        <dd class="col-sm-9">{{ $cliente->cidade }}/{{ $cliente->estado ?? '' }}</dd>
                    @endif

                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $cliente->ativo ? 'success' : 'secondary' }}">
                            {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        @if($cliente->processos && $cliente->processos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Processos ({{ $cliente->processos->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Status</th>
                                    <th>Data Abertura</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->processos as $processo)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.processos.show', $processo->id) }}">
                                                {{ $processo->numero_processo ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($processo->status)
                                                <span class="badge bg-{{ $processo->status->color() }}">
                                                    {{ $processo->status->label() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $processo->data_abertura?->format('d/m/Y') ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection



