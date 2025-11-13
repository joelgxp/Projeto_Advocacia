@extends('layouts.app')

@section('title', 'Dashboard - Recepção')
@section('page-title', 'Dashboard - Recepção')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Bem-vindo ao Painel de Recepção</h5>
            </div>
            <div class="modern-card-body">
                <p class="text-muted mb-3">Use o menu lateral para navegar pelas funcionalidades do sistema.</p>
                <p class="mb-2"><strong>Áreas disponíveis:</strong></p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Gestão de Clientes</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Gestão de Processos</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Audiências</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Controle Financeiro</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

