@extends('layouts.app')

@section('title', 'Detalhes do Processo - Administrador')

@section('page-title', 'Detalhes do Processo')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Cabeçalho -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Processo: {{ $numero }}
                </h5>
                <div>
                    <a href="{{ route('admin.consulta-processual.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                    @if($processo)
                        <a href="{{ route('admin.processos.show', $processo->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> Ver no Sistema
                        </a>
                    @else
                        <a href="{{ route('admin.processos.create', ['numero_processo' => $numero]) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Cadastrar no Sistema
                        </a>
                    @endif
                    <a href="{{ route('admin.consulta-processual.historico', $numero) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-history me-1"></i> Histórico
                    </a>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            // Estrutura de dados da API DataJud (Elasticsearch)
            $hits = $dados['hits']['hits'] ?? [];
            $processoData = !empty($hits) ? $hits[0]['_source'] ?? [] : [];
            
            // Informações básicas
            $numeroProcesso = $processoData['numeroProcesso'] ?? $numero;
            $classe = $processoData['classe']['nome'] ?? 'Não informado';
            $classeCodigo = $processoData['classe']['codigo'] ?? null;
            $assuntos = $processoData['assuntos'] ?? [];
            $sistema = $processoData['sistema']['nome'] ?? ($processoData['sistema'] ?? 'Não informado');
            $sistemaCodigo = $processoData['sistema']['codigo'] ?? null;
            $formato = $processoData['formato']['nome'] ?? ($processoData['formato'] ?? 'Não informado');
            $formatoCodigo = $processoData['formato']['codigo'] ?? null;
            $grau = $processoData['grau'] ?? null;
            $tribunalNome = $processoData['tribunal'] ?? 'Não informado';
            
            // Datas
            $dataAjuizamento = $processoData['dataAjuizamento'] ?? null;
            $dataHoraUltimaAtualizacao = $processoData['dataHoraUltimaAtualizacao'] ?? null;
            
            // Órgão Julgador
            $orgaoJulgador = $processoData['orgaoJulgador'] ?? [];
            
            // Partes do processo (pessoas envolvidas - pode não existir em todos os processos)
            $partesEnvolvidas = $processoData['partes'] ?? [];
            $poloAtivo = collect($partesEnvolvidas)->where('tipo', 'Polo Ativo')->first() ?? null;
            $poloPassivo = collect($partesEnvolvidas)->where('tipo', 'Polo Passivo')->first() ?? null;
            
            // $partes vem do controller (extraído do número do processo CNJ)
            // Contém: segmento, tribunal, numero_limpo, origem, etc.
            
            // Movimentações
            $movimentacoes = $processoData['movimentos'] ?? [];
            
            // Ordenar movimentações por data (mais recente primeiro)
            if (!empty($movimentacoes)) {
                usort($movimentacoes, function($a, $b) {
                    $dataA = $a['dataHora'] ?? '';
                    $dataB = $b['dataHora'] ?? '';
                    return strcmp($dataB, $dataA);
                });
            }
        @endphp

        @if(empty($processoData))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Atenção:</strong> Nenhum dado encontrado para este processo na API.
            </div>
        @else
            <!-- Informações Básicas -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informações Básicas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Número do Processo:</strong><br>
                            <span class="text-primary">{{ $numeroProcesso }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Classe:</strong><br>
                            {{ $classe }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Assunto(s):</strong><br>
                            @if(!empty($assuntos))
                                @foreach($assuntos as $item)
                                    {{ $item['nome'] ?? '' }}@if(isset($item['codigo'])) ({{ $item['codigo'] }})@endif{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            @else
                                Não informado
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Sistema:</strong><br>
                            {{ $sistema }}@if($sistemaCodigo) ({{ $sistemaCodigo }})@endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Formato:</strong><br>
                            {{ $formato }}@if($formatoCodigo) ({{ $formatoCodigo }})@endif
                        </div>
                        @if($grau)
                        <div class="col-md-6 mb-3">
                            <strong>Grau:</strong><br>
                            {{ $grau }}
                        </div>
                        @endif
                        @if($classeCodigo)
                        <div class="col-md-6 mb-3">
                            <strong>Código da Classe:</strong><br>
                            {{ $classeCodigo }}
                        </div>
                        @endif
                        @if($dataAjuizamento)
                        <div class="col-md-6 mb-3">
                            <strong>Data de Ajuizamento:</strong><br>
                            @php
                                // Formato: YYYYMMDDHHMMSS
                                $ano = substr($dataAjuizamento, 0, 4);
                                $mes = substr($dataAjuizamento, 4, 2);
                                $dia = substr($dataAjuizamento, 6, 2);
                                $dataFormatada = $dia . '/' . $mes . '/' . $ano;
                            @endphp
                            {{ $dataFormatada }}
                        </div>
                        @endif
                        @if($dataHoraUltimaAtualizacao)
                        <div class="col-md-6 mb-3">
                            <strong>Última Atualização:</strong><br>
                            {{ \Carbon\Carbon::parse($dataHoraUltimaAtualizacao)->format('d/m/Y H:i') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Órgão Julgador e Tribunal -->
            <div class="row mb-3">
                @if(!empty($orgaoJulgador))
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-gavel me-2"></i>Órgão Julgador
                            </h6>
                        </div>
                        <div class="card-body">
                            <strong>Nome:</strong> {{ $orgaoJulgador['nome'] ?? 'Não informado' }}<br>
                            @if(isset($orgaoJulgador['codigo']))
                            <strong>Código:</strong> {{ $orgaoJulgador['codigo'] }}<br>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-balance-scale me-2"></i>Tribunal
                            </h6>
                        </div>
                        <div class="card-body">
                            <strong>Nome:</strong> {{ $tribunalNome }}<br>
                            @if(isset($partes['tribunal']))
                            <strong>Código TR:</strong> {{ $partes['tribunal'] }}<br>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partes Envolvidas -->
            @if(!empty($partesEnvolvidas))
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>Partes Envolvidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($poloAtivo)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-success">
                                <i class="fas fa-user-check me-1"></i>Polo Ativo
                            </h6>
                            @if(isset($poloAtivo['pessoas']))
                                @foreach($poloAtivo['pessoas'] as $pessoa)
                                    <div class="mb-2">
                                        <strong>{{ $pessoa['nome'] ?? 'Não informado' }}</strong><br>
                                        @if(isset($pessoa['tipo']))
                                        <small class="text-muted">Tipo: {{ $pessoa['tipo'] }}</small><br>
                                        @endif
                                        @if(isset($pessoa['documento']))
                                        <small class="text-muted">Documento: {{ $pessoa['documento'] }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @endif

                        @if($poloPassivo)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-danger">
                                <i class="fas fa-user-times me-1"></i>Polo Passivo
                            </h6>
                            @if(isset($poloPassivo['pessoas']))
                                @foreach($poloPassivo['pessoas'] as $pessoa)
                                    <div class="mb-2">
                                        <strong>{{ $pessoa['nome'] ?? 'Não informado' }}</strong><br>
                                        @if(isset($pessoa['tipo']))
                                        <small class="text-muted">Tipo: {{ $pessoa['tipo'] }}</small><br>
                                        @endif
                                        @if(isset($pessoa['documento']))
                                        <small class="text-muted">Documento: {{ $pessoa['documento'] }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Últimas Movimentações -->
            @if(!empty($movimentacoes))
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Últimas Movimentações
                    </h6>
                    <a href="{{ route('admin.consulta-processual.historico', $numero) }}" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach(array_slice($movimentacoes, 0, 5) as $movimento)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="timeline-marker me-3">
                                        <i class="fas fa-circle text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            {{ $movimento['nome'] ?? 'Movimentação' }}
                                            @if(isset($movimento['codigo']))
                                            <small class="text-muted">({{ $movimento['codigo'] }})</small>
                                            @endif
                                        </h6>
                                        @if(isset($movimento['dataHora']))
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($movimento['dataHora'])->format('d/m/Y H:i') }}
                                        </small>
                                        @endif
                                        @if(isset($movimento['complementosTabelados']) && !empty($movimento['complementosTabelados']))
                                        <div class="mt-2">
                                            @foreach($movimento['complementosTabelados'] as $complemento)
                                                <span class="badge bg-secondary me-1">
                                                    {{ $complemento['nome'] ?? '' }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Informações Técnicas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Informações Técnicas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Segmento:</strong> {{ $partes['segmento'] ?? 'N/A' }} - 
                            @php
                                $segmentos = [
                                    '1' => 'Justiça Federal',
                                    '2' => 'Justiça Eleitoral',
                                    '3' => 'Justiça do Trabalho',
                                    '4' => 'Justiça Militar',
                                    '5' => 'Justiça Estadual',
                                ];
                                echo $segmentos[$partes['segmento'] ?? ''] ?? 'Desconhecido';
                            @endphp
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Tribunal (TR):</strong> {{ $partes['tribunal'] ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Número Limpo:</strong> {{ $partes['numero_limpo'] ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Origem:</strong> {{ $partes['origem'] ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 20px;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 5px;
    }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -20px;
        top: 20px;
        bottom: -15px;
        width: 2px;
        background: #dee2e6;
    }
</style>
@endpush
@endsection
