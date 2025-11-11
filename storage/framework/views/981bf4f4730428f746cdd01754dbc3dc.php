

<?php $__env->startSection('title', 'Detalhes do Processo - Administrador'); ?>

<?php $__env->startSection('page-title', 'Detalhes do Processo'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <!-- Cabeçalho -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Processo: <?php echo e($numero); ?>

                </h5>
                <div>
                    <a href="<?php echo e(route('admin.consulta-processual.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                    <?php if($processo): ?>
                        <a href="<?php echo e(route('admin.processos.show', $processo->id)); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> Ver no Sistema
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('admin.processos.create', ['numero_processo' => $numero])); ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Cadastrar no Sistema
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.consulta-processual.historico', $numero)); ?>" class="btn btn-sm btn-info">
                        <i class="fas fa-history me-1"></i> Histórico
                    </a>
                </div>
            </div>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php
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
        ?>

        <?php if(empty($processoData)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Atenção:</strong> Nenhum dado encontrado para este processo na API.
            </div>
        <?php else: ?>
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
                            <span class="text-primary"><?php echo e($numeroProcesso); ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Classe:</strong><br>
                            <?php echo e($classe); ?>

                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Assunto(s):</strong><br>
                            <?php if(!empty($assuntos)): ?>
                                <?php $__currentLoopData = $assuntos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($item['nome'] ?? ''); ?><?php if(isset($item['codigo'])): ?> (<?php echo e($item['codigo']); ?>)<?php endif; ?><?php echo e(!$loop->last ? ', ' : ''); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                Não informado
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Sistema:</strong><br>
                            <?php echo e($sistema); ?><?php if($sistemaCodigo): ?> (<?php echo e($sistemaCodigo); ?>)<?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Formato:</strong><br>
                            <?php echo e($formato); ?><?php if($formatoCodigo): ?> (<?php echo e($formatoCodigo); ?>)<?php endif; ?>
                        </div>
                        <?php if($grau): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Grau:</strong><br>
                            <?php echo e($grau); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($classeCodigo): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Código da Classe:</strong><br>
                            <?php echo e($classeCodigo); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($dataAjuizamento): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Data de Ajuizamento:</strong><br>
                            <?php
                                // Formato: YYYYMMDDHHMMSS
                                $ano = substr($dataAjuizamento, 0, 4);
                                $mes = substr($dataAjuizamento, 4, 2);
                                $dia = substr($dataAjuizamento, 6, 2);
                                $dataFormatada = $dia . '/' . $mes . '/' . $ano;
                            ?>
                            <?php echo e($dataFormatada); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($dataHoraUltimaAtualizacao): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Última Atualização:</strong><br>
                            <?php echo e(\Carbon\Carbon::parse($dataHoraUltimaAtualizacao)->format('d/m/Y H:i')); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Órgão Julgador e Tribunal -->
            <div class="row mb-3">
                <?php if(!empty($orgaoJulgador)): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-gavel me-2"></i>Órgão Julgador
                            </h6>
                        </div>
                        <div class="card-body">
                            <strong>Nome:</strong> <?php echo e($orgaoJulgador['nome'] ?? 'Não informado'); ?><br>
                            <?php if(isset($orgaoJulgador['codigo'])): ?>
                            <strong>Código:</strong> <?php echo e($orgaoJulgador['codigo']); ?><br>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-balance-scale me-2"></i>Tribunal
                            </h6>
                        </div>
                        <div class="card-body">
                            <strong>Nome:</strong> <?php echo e($tribunalNome); ?><br>
                            <?php if(isset($partes['tribunal'])): ?>
                            <strong>Código TR:</strong> <?php echo e($partes['tribunal']); ?><br>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partes Envolvidas -->
            <?php if(!empty($partesEnvolvidas)): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>Partes Envolvidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if($poloAtivo): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-success">
                                <i class="fas fa-user-check me-1"></i>Polo Ativo
                            </h6>
                            <?php if(isset($poloAtivo['pessoas'])): ?>
                                <?php $__currentLoopData = $poloAtivo['pessoas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pessoa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-2">
                                        <strong><?php echo e($pessoa['nome'] ?? 'Não informado'); ?></strong><br>
                                        <?php if(isset($pessoa['tipo'])): ?>
                                        <small class="text-muted">Tipo: <?php echo e($pessoa['tipo']); ?></small><br>
                                        <?php endif; ?>
                                        <?php if(isset($pessoa['documento'])): ?>
                                        <small class="text-muted">Documento: <?php echo e($pessoa['documento']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if($poloPassivo): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-danger">
                                <i class="fas fa-user-times me-1"></i>Polo Passivo
                            </h6>
                            <?php if(isset($poloPassivo['pessoas'])): ?>
                                <?php $__currentLoopData = $poloPassivo['pessoas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pessoa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-2">
                                        <strong><?php echo e($pessoa['nome'] ?? 'Não informado'); ?></strong><br>
                                        <?php if(isset($pessoa['tipo'])): ?>
                                        <small class="text-muted">Tipo: <?php echo e($pessoa['tipo']); ?></small><br>
                                        <?php endif; ?>
                                        <?php if(isset($pessoa['documento'])): ?>
                                        <small class="text-muted">Documento: <?php echo e($pessoa['documento']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Últimas Movimentações -->
            <?php if(!empty($movimentacoes)): ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Últimas Movimentações
                    </h6>
                    <a href="<?php echo e(route('admin.consulta-processual.historico', $numero)); ?>" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php $__currentLoopData = array_slice($movimentacoes, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="timeline-marker me-3">
                                        <i class="fas fa-circle text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <?php echo e($movimento['nome'] ?? 'Movimentação'); ?>

                                            <?php if(isset($movimento['codigo'])): ?>
                                            <small class="text-muted">(<?php echo e($movimento['codigo']); ?>)</small>
                                            <?php endif; ?>
                                        </h6>
                                        <?php if(isset($movimento['dataHora'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo e(\Carbon\Carbon::parse($movimento['dataHora'])->format('d/m/Y H:i')); ?>

                                        </small>
                                        <?php endif; ?>
                                        <?php if(isset($movimento['complementosTabelados']) && !empty($movimento['complementosTabelados'])): ?>
                                        <div class="mt-2">
                                            <?php $__currentLoopData = $movimento['complementosTabelados']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complemento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-secondary me-1">
                                                    <?php echo e($complemento['nome'] ?? ''); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Informações Técnicas -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Informações Técnicas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Segmento:</strong> <?php echo e($partes['segmento'] ?? 'N/A'); ?> - 
                            <?php echo e(\App\Helpers\ProcessoCNJHelper::getNomeSegmento($partes['segmento'] ?? '')); ?>

                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Tribunal (TR):</strong> <?php echo e($partes['tribunal'] ?? 'N/A'); ?>

                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Número Limpo:</strong> <?php echo e($partes['numero_limpo'] ?? 'N/A'); ?>

                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Origem:</strong> <?php echo e($partes['origem'] ?? 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <!-- Debug - Informações da API -->
        <?php if(isset($debug) && $debug): ?>
        <div class="card border-warning mb-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-bug me-2"></i>Debug - Informações da Consulta API
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <strong>URL da API utilizada:</strong><br>
                        <code class="d-block p-2 bg-light border rounded mt-1" style="word-break: break-all; font-size: 0.9em;">
                            <?php echo e($debug['url'] ?? 'N/A'); ?>

                        </code>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Segmento (J):</strong> <?php echo e($debug['segmento'] ?? 'N/A'); ?>

                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Tribunal (TR):</strong> <?php echo e($debug['tribunal'] ?? 'N/A'); ?>

                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Número Limpo:</strong> <?php echo e($debug['numero_limpo'] ?? 'N/A'); ?>

                    </div>
                    <div class="col-md-12 mb-2">
                        <strong>Número Formatado:</strong> <?php echo e($debug['numero_formatado'] ?? 'N/A'); ?>

                    </div>
                    <?php if(isset($debug['endpoint_base'])): ?>
                    <div class="col-md-12 mb-2">
                        <strong>Endpoint Base:</strong> <?php echo e($debug['endpoint_base'] ?? 'N/A'); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Estas informações são exibidas apenas para fins de debug e desenvolvimento.
                    </small>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/consulta-processual/detalhes.blade.php ENDPATH**/ ?>