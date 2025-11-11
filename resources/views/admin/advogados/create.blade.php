@extends('layouts.app')

@section('title', 'Novo Advogado - Administrador')

@section('page-title', 'Novo Advogado')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Cadastrar Novo Advogado</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.advogados.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF *</label>
                    <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                           id="cpf" name="cpf" value="{{ old('cpf') }}" required>
                    @error('cpf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="oab" class="form-label">OAB</label>
                    <input type="text" class="form-control @error('oab') is-invalid @enderror" 
                           id="oab" name="oab" value="{{ old('oab') }}">
                    @error('oab')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Senha *</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                           id="telefone" name="telefone" value="{{ old('telefone') }}">
                    @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="biografia" class="form-label">Biografia</label>
                <textarea class="form-control @error('biografia') is-invalid @enderror" 
                          id="biografia" name="biografia" rows="3">{{ old('biografia') }}</textarea>
                @error('biografia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.advogados.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar Advogado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection