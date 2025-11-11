

<?php $__env->startSection('title', 'Dashboard - Administrador'); ?>

<?php $__env->startSection('page-title', 'Dashboard Administrativo'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2">Processos Ativos</h6>
                        <h2 class="mb-0"><?php echo e($processosAtivos); ?></h2>
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
                        <h2 class="mb-0"><?php echo e($totalClientes); ?></h2>
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
                        <h2 class="mb-0"><?php echo e($prazosProximos); ?></h2>
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
                        <h2 class="mb-0"><?php echo e($audienciasProximas); ?></h2>
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
                <?php
                    $processosRecentes = \App\Models\Processo::with('cliente', 'advogado')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                ?>
                
                <?php if($processosRecentes->count() > 0): ?>
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
                                <?php $__currentLoopData = $processosRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $processo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('admin.processos.show', $processo->id)); ?>">
                                                <?php echo e($processo->numero_processo); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($processo->cliente->nome ?? 'N/A'); ?></td>
                                        <td><?php echo e($processo->advogado->user->name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($processo->status): ?>
                                                <span class="badge bg-<?php echo e($processo->status->color()); ?>">
                                                    <?php echo e($processo->status->label()); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($processo->created_at->format('d/m/Y')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>Nenhum processo encontrado.
                    </p>
                <?php endif; ?>
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
                <?php
                    $proximasAudiencias = \App\Models\Audiencia::with('processo')
                        ->where('status', 'agendada')
                        ->where('data', '>=', now())
                        ->orderBy('data', 'asc')
                        ->limit(5)
                        ->get();
                ?>
                
                <?php if($proximasAudiencias->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $proximasAudiencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audiencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($audiencia->processo->numero_processo ?? 'N/A'); ?></h6>
                                        <p class="mb-1 text-muted small"><?php echo e($audiencia->tipo ?? 'Audiência'); ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo e(\Carbon\Carbon::parse($audiencia->data)->format('d/m/Y H:i')); ?>

                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>Nenhuma audiência agendada.
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Prazos Urgentes
                </h5>
            </div>
            <div class="card-body">
                <?php
                    $prazosUrgentes = \App\Models\Prazo::with('processo')
                        ->where('status', 'pendente')
                        ->where('data_vencimento', '<=', now()->addDays(3))
                        ->orderBy('data_vencimento', 'asc')
                        ->limit(5)
                        ->get();
                ?>
                
                <?php if($prazosUrgentes->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $prazosUrgentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prazo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($prazo->processo->numero_processo ?? 'N/A'); ?></h6>
                                        <p class="mb-1 text-muted small"><?php echo e($prazo->tipo ?? 'Prazo'); ?></p>
                                        <small class="text-<?php echo e(\Carbon\Carbon::parse($prazo->data_vencimento)->isPast() ? 'danger' : 'warning'); ?>">
                                            <i class="fas fa-clock me-1"></i>
                                            Vence em: <?php echo e(\Carbon\Carbon::parse($prazo->data_vencimento)->format('d/m/Y')); ?>

                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        <i class="fas fa-check-circle me-2"></i>Nenhum prazo urgente.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>