@extends('layouts.app')

@section('title', 'Criar Processo')
@section('page-title', 'Criar Processo')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Novo Processo</h5>
            </div>
            <div class="modern-card-body">
                <form method="POST" action="{{ route('admin.processos.store') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">Cliente *</label>
                            <select name="cliente_id" class="form-control-modern" required>
                                <option value="">Selecione um cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }} - {{ $cliente->cpf_cnpj }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Advogado *</label>
                            <select name="advogado_id" class="form-control-modern" required>
                                <option value="">Selecione um advogado</option>
                                @foreach($advogados as $advogado)
                                    <option value="{{ $advogado->id }}">{{ $advogado->user->name }} - OAB: {{ $advogado->oab }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">Número CNJ</label>
                            <input type="text" name="numero_cnj" class="form-control-modern" placeholder="0000000-00.0000.0.00.0000">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Vara *</label>
                            <select name="vara_id" class="form-control-modern" required>
                                <option value="">Selecione uma vara</option>
                                @foreach($varas as $vara)
                                    <option value="{{ $vara->id }}">{{ $vara->nome }} - {{ $vara->comarca }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">Especialidade</label>
                            <select name="especialidade_id" class="form-control-modern">
                                <option value="">Selecione uma especialidade</option>
                                @foreach($especialidades as $especialidade)
                                    <option value="{{ $especialidade->id }}">{{ $especialidade->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Status</label>
                            <select name="status" class="form-control-modern">
                                <option value="andamento">Em Andamento</option>
                                <option value="arquivado">Arquivado</option>
                                <option value="suspenso">Suspenso</option>
                                <option value="encerrado">Encerrado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-modern">Descrição</label>
                        <textarea name="descricao" class="form-control-modern" rows="4" placeholder="Descrição do processo"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.processos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-modern btn-modern-primary">Salvar Processo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

