@extends('layouts.app')

@section('title', 'Processos')
@section('page-title', 'Processos')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Lista de Processos</h5>
                <a href="{{ route('admin.processos.create') }}" class="btn btn-modern btn-modern-primary">
                    <i class="fas fa-plus me-2"></i> Novo Processo
                </a>
            </div>
            <div class="modern-card-body">
                @if($processos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Advogado</th>
                                <th>Vara</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($processos as $processo)
                            <tr>
                                <td>{{ $processo->numero_processo ?? '-' }}</td>
                                <td>{{ $processo->cliente->nome ?? '-' }}</td>
                                <td>{{ $processo->advogado->user->name ?? '-' }}</td>
                                <td>{{ $processo->vara->nome ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $processo->status ?? '-' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.processos.show', $processo) }}" class="btn btn-sm btn-modern btn-modern-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.processos.edit', $processo) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                <p class="text-muted text-center py-4">Nenhum processo encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

