@extends('layouts.app')

@section('title', 'Dashboard - Admin')
@section('page-title', 'Dashboard - Administrador')

@section('content')
<div class="row mb-4 fade-in">
    <div class="col-md-3 mb-4">
        <div class="stat-card primary">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <h6>Processos Ativos</h6>
                    <h2>{{ $processosAtivos ?? 0 }}</h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-gavel"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stat-card success">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <h6>Total de Clientes</h6>
                    <h2>{{ $totalClientes ?? 0 }}</h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stat-card warning">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <h6>Prazos Próximos</h6>
                    <h2>{{ $prazosProximos ?? 0 }}</h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stat-card info">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <h6>Audiências Próximas</h6>
                    <h2>{{ $audienciasProximas ?? 0 }}</h2>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Bem-vindo ao Painel Administrativo</h5>
            </div>
            <div class="modern-card-body">
                <p class="text-muted mb-0">Use o menu lateral para navegar pelas funcionalidades do sistema.</p>
            </div>
        </div>
    </div>
</div>
@endsection

