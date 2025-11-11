@extends('layouts.app')

@section('title', 'Novo Cliente - Administrador')

@section('page-title', 'Novo Cliente')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Cadastrar Novo Cliente</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clientes.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="nome" class="form-label">Nome Completo / Razão Social *</label>
                    <input type="text" 
                           class="form-control @error('nome') is-invalid @enderror" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}"
                           required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="tipo_pessoa" class="form-label">Tipo *</label>
                    <select class="form-select @error('tipo_pessoa') is-invalid @enderror" 
                            id="tipo_pessoa" 
                            name="tipo_pessoa" 
                            required>
                        <option value="">Selecione...</option>
                        <option value="PF" {{ old('tipo_pessoa') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                        <option value="PJ" {{ old('tipo_pessoa') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                    </select>
                    @error('tipo_pessoa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf_cnpj" class="form-label">CPF / CNPJ *</label>
                    <input type="text" 
                           class="form-control @error('cpf_cnpj') is-invalid @enderror" 
                           id="cpf_cnpj" 
                           name="cpf_cnpj" 
                           value="{{ old('cpf_cnpj') }}"
                           required>
                    @error('cpf_cnpj')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" 
                           class="form-control @error('telefone') is-invalid @enderror" 
                           id="telefone" 
                           name="telefone" 
                           value="{{ old('telefone') }}">
                    @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="celular" class="form-label">Celular</label>
                    <input type="text" 
                           class="form-control @error('celular') is-invalid @enderror" 
                           id="celular" 
                           name="celular" 
                           value="{{ old('celular') }}">
                    @error('celular')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" 
                       class="form-control @error('endereco') is-invalid @enderror" 
                       id="endereco" 
                       name="endereco" 
                       value="{{ old('endereco') }}">
                @error('endereco')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" 
                           class="form-control @error('cidade') is-invalid @enderror" 
                           id="cidade" 
                           name="cidade" 
                           value="{{ old('cidade') }}">
                    @error('cidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" 
                           class="form-control @error('estado') is-invalid @enderror" 
                           id="estado" 
                           name="estado" 
                           value="{{ old('estado') }}"
                           maxlength="2"
                           placeholder="SP">
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" 
                           class="form-control @error('cep') is-invalid @enderror" 
                           id="cep" 
                           name="cep" 
                           value="{{ old('cep') }}">
                    @error('cep')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


