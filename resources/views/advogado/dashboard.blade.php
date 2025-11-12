@extends('layouts.app')

@section('title', 'Dashboard - Advogado')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard - Advogado</h1>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Processos Ativos</h6>
                        <h2 class="mb-0">{{ $processosAtivos ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-gavel fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Prazos Próximos</h6>
                        <h2 class="mb-0">{{ $prazosProximos ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Audiências Próximas</h6>
                        <h2 class="mb-0">{{ $audienciasProximas ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Bem-vindo ao Painel do Advogado</h5>
            </div>
            <div class="card-body">
                <p>Use o menu lateral para navegar pelas funcionalidades do sistema.</p>
            </div>
        </div>
    </div>
</div>
@endsection

