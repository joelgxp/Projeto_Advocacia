@extends('layouts.app')

@section('title', 'Editar Processo - Administrador')

@section('page-title', 'Editar Processo')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar Processo</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.processos.update', $processo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="numero_processo" class="form-label">Número do Processo</label>
                    <input type="text" 
                           class="form-control @error('numero_processo') is-invalid @enderror" 
                           id="numero_processo" 
                           name="numero_processo" 
                           value="{{ old('numero_processo', $processo->numero_processo) }}"
                           placeholder="Ex: 0000123-45.2023.8.26.0100">
                    @error('numero_processo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="">Selecione...</option>
                        <option value="aberto" {{ old('status', $processo->status?->value) == 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="andamento" {{ old('status', $processo->status?->value) == 'andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="concluido" {{ old('status', $processo->status?->value) == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="arquivado" {{ old('status', $processo->status?->value) == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                        <option value="cancelado" {{ old('status', $processo->status?->value) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cliente_id" class="form-label">Cliente *</label>
                    <select class="form-select @error('cliente_id') is-invalid @enderror" 
                            id="cliente_id" 
                            name="cliente_id" 
                            required>
                        <option value="">Selecione o cliente...</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $processo->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }} - {{ $cliente->cpf_cnpj }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="advogado_id" class="form-label">Advogado Responsável *</label>
                    <select class="form-select @error('advogado_id') is-invalid @enderror" 
                            id="advogado_id" 
                            name="advogado_id" 
                            required>
                        <option value="">Selecione o advogado...</option>
                        @foreach($advogados as $advogado)
                            <option value="{{ $advogado->id }}" {{ old('advogado_id', $processo->advogado_id) == $advogado->id ? 'selected' : '' }}>
                                {{ $advogado->user->name }} - OAB: {{ $advogado->oab ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('advogado_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="vara_id" class="form-label">Vara *</label>
                    <select class="form-select @error('vara_id') is-invalid @enderror" 
                            id="vara_id" 
                            name="vara_id" 
                            required>
                        <option value="">Selecione a vara...</option>
                        @foreach($varas as $vara)
                            <option value="{{ $vara->id }}" {{ old('vara_id', $processo->vara_id) == $vara->id ? 'selected' : '' }}>
                                {{ $vara->nome }} - {{ $vara->comarca }}
                            </option>
                        @endforeach
                    </select>
                    @error('vara_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="especialidade_id" class="form-label">Especialidade *</label>
                    <select class="form-select @error('especialidade_id') is-invalid @enderror" 
                            id="especialidade_id" 
                            name="especialidade_id" 
                            required>
                        <option value="">Selecione a especialidade...</option>
                        @foreach($especialidades as $especialidade)
                            <option value="{{ $especialidade->id }}" {{ old('especialidade_id', $processo->especialidade_id) == $especialidade->id ? 'selected' : '' }}>
                                {{ $especialidade->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('especialidade_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_abertura" class="form-label">Data de Abertura *</label>
                    <input type="date" 
                           class="form-control @error('data_abertura') is-invalid @enderror" 
                           id="data_abertura" 
                           name="data_abertura" 
                           value="{{ old('data_abertura', $processo->data_abertura?->format('Y-m-d')) }}"
                           required>
                    @error('data_abertura')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data_peticao" class="form-label">Data da Petição</label>
                    <input type="date" 
                           class="form-control @error('data_peticao') is-invalid @enderror" 
                           id="data_peticao" 
                           name="data_peticao" 
                           value="{{ old('data_peticao', $processo->data_peticao?->format('Y-m-d')) }}">
                    @error('data_peticao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="observacoes" class="form-label">Observações</label>
                <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                          id="observacoes" 
                          name="observacoes" 
                          rows="3">{{ old('observacoes', $processo->observacoes) }}</textarea>
                @error('observacoes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.processos.show', $processo->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar Processo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


