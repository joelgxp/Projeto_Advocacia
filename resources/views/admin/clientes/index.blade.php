@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Lista de Clientes</h5>
                <a href="{{ route('admin.clientes.create') }}" class="btn btn-modern btn-modern-primary">
                    <i class="fas fa-plus me-2"></i> Novo Cliente
                </a>
            </div>
            <div class="modern-card-body">
                @if($clientes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF/CNPJ</th>
                                <th>Tipo</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->nome }}</td>
                                <td>{{ $cliente->cpf_cnpj }}</td>
                                <td><span class="badge bg-info">{{ $cliente->tipo_pessoa }}</span></td>
                                <td>{{ $cliente->email ?? '-' }}</td>
                                <td>{{ $cliente->telefone ?? $cliente->celular ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $cliente->ativo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.clientes.show', $cliente) }}" class="btn btn-sm btn-modern btn-modern-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $clientes->links() }}
                </div>
                @else
                <p class="text-muted text-center py-4">Nenhum cliente encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

