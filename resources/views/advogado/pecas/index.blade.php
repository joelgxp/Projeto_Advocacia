@extends('layouts.app')

@section('title', 'Peças Geradas por IA')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-robot"></i> Peças Geradas por IA</h2>
                <a href="{{ route('advogado.pecas.criar') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Gerar Nova Peça
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Processos Disponíveis</h5>
                </div>
                <div class="card-body">
                    @if($processos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Número do Processo</th>
                                        <th>Cliente</th>
                                        <th>Especialidade</th>
                                        <th>Status</th>
                                        <th>Data Abertura</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processos as $processo)
                                        <tr>
                                            <td>
                                                <strong>{{ $processo->numero_processo }}</strong>
                                            </td>
                                            <td>{{ $processo->cliente->nome ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $processo->especialidade->nome ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $processo->status_color }}">
                                                    {{ $processo->status_label }}
                                                </span>
                                            </td>
                                            <td>{{ $processo->data_abertura?->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('advogado.pecas.criar', ['processo_id' => $processo->id]) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Gerar Peça">
                                                    <i class="fas fa-robot"></i> Gerar Peça
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
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Nenhum processo encontrado. Cadastre processos para gerar peças com IA.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
