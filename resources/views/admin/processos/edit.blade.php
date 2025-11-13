@extends('layouts.app')

@section('title', 'Editar Processo')
@section('page-title', 'Editar Processo')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Editar Processo #{{ $processo->id }}</h5>
            </div>
            <div class="modern-card-body">
                <form method="POST" action="{{ route('admin.processos.update', $processo) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">Cliente *</label>
                            <select name="cliente_id" class="form-control-modern" required>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ $processo->cliente_id == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }} - {{ $cliente->cpf_cnpj }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Advogado *</label>
                            <select name="advogado_id" class="form-control-modern" required>
                                @foreach($advogados as $advogado)
                                    <option value="{{ $advogado->id }}" {{ $processo->advogado_id == $advogado->id ? 'selected' : '' }}>
                                        {{ $advogado->user->name }} - OAB: {{ $advogado->oab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">Número CNJ</label>
                            <input type="text" name="numero_cnj" class="form-control-modern" 
                                   value="{{ $processo->numero_cnj }}" placeholder="0000000-00.0000.0.00.0000">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Status</label>
                            <select name="status" class="form-control-modern">
                                <option value="andamento" {{ $processo->status == 'andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="arquivado" {{ $processo->status == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                                <option value="suspenso" {{ $processo->status == 'suspenso' ? 'selected' : '' }}>Suspenso</option>
                                <option value="encerrado" {{ $processo->status == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.processos.show', $processo) }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-modern btn-modern-primary">Atualizar Processo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

