@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2">Processos Ativos</h6>
                        <h2 class="mb-0">{{ $processosAtivos }}</h2>
                    </div>
                    <i class="fas fa-folder-open fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2">Total de Clientes</h6>
                        <h2 class="mb-0">{{ $totalClientes }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2">Prazos Próximos</h6>
                        <h2 class="mb-0">{{ $prazosProximos }}</h2>
                        <small class="text-white-50">Próximos 7 dias</small>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2">Audiências</h6>
                        <h2 class="mb-0">{{ $audienciasProximas }}</h2>
                        <small class="text-white-50">Próximos 30 dias</small>
                    </div>
                    <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-folder-open me-2"></i>Processos Recentes
                </h5>
            </div>
            <div class="card-body">
                @php
                    $processosRecentes = \App\Models\Processo::with('cliente', 'advogado')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                @endphp
                
                @if($processosRecentes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Advogado</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($processosRecentes as $processo)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.processos.show', $processo->id) }}">
                                                {{ $processo->numero_processo }}
                                            </a>
                                        </td>
                                        <td>{{ $processo->cliente->nome ?? 'N/A' }}</td>
                                        <td>{{ $processo->advogado->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($processo->status)
                                                <span class="badge bg-{{ $processo->status->color() }}">
                                                    {{ $processo->status->label() }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $processo->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>Nenhum processo encontrado.
                    </p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>Próximas Audiências
                </h5>
            </div>
            <div class="card-body">
                @php
                    $proximasAudiencias = \App\Models\Audiencia::with('processo')
                        ->where('status', 'agendada')
                        ->where('data', '>=', now())
                        ->orderBy('data', 'asc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($proximasAudiencias->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($proximasAudiencias as $audiencia)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $audiencia->processo->numero_processo ?? 'N/A' }}</h6>
                                        <p class="mb-1 text-muted small">{{ $audiencia->tipo ?? 'Audiência' }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($audiencia->data)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>Nenhuma audiência agendada.
                    </p>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Prazos Urgentes
                </h5>
            </div>
            <div class="card-body">
                @php
                    $prazosUrgentes = \App\Models\Prazo::with('processo')
                        ->where('status', 'pendente')
                        ->where('data_vencimento', '<=', now()->addDays(3))
                        ->orderBy('data_vencimento', 'asc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($prazosUrgentes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($prazosUrgentes as $prazo)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $prazo->processo->numero_processo ?? 'N/A' }}</h6>
                                        <p class="mb-1 text-muted small">{{ $prazo->tipo ?? 'Prazo' }}</p>
                                        <small class="text-{{ \Carbon\Carbon::parse($prazo->data_vencimento)->isPast() ? 'danger' : 'warning' }}">
                                            <i class="fas fa-clock me-1"></i>
                                            Vence em: {{ \Carbon\Carbon::parse($prazo->data_vencimento)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-check-circle me-2"></i>Nenhum prazo urgente.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

