@extends('layouts.app')

@section('title', 'Novo Cliente')
@section('page-title', 'Novo Cliente')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Criar Novo Cliente</h5>
            </div>
            <div class="modern-card-body">
                <form action="{{ route('admin.clientes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Nome *</label>
                            <input type="text" name="nome" class="form-control form-control-modern" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">CPF/CNPJ *</label>
                            <input type="text" name="cpf_cnpj" class="form-control form-control-modern" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Tipo de Pessoa *</label>
                            <select name="tipo_pessoa" class="form-control form-control-modern" required>
                                <option value="PF">Pessoa Física</option>
                                <option value="PJ">Pessoa Jurídica</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">E-mail</label>
                            <input type="email" name="email" class="form-control form-control-modern">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Telefone</label>
                            <input type="text" name="telefone" class="form-control form-control-modern">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Celular</label>
                            <input type="text" name="celular" class="form-control form-control-modern">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-modern btn-modern-primary">
                            <i class="fas fa-save me-2"></i> Salvar
                        </button>
                        <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

