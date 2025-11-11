

<?php $__env->startSection('title', 'Detalhes do Processo - Administrador'); ?>

<?php $__env->startSection('page-title', 'Detalhes do Processo'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <a href="<?php echo e(route('admin.processos.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
        <a href="<?php echo e(route('admin.processos.edit', $processo->id)); ?>" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informações do Processo</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Número:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->numero_processo ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        <?php if($processo->status): ?>
                            <span class="badge bg-<?php echo e($processo->status->color()); ?>">
                                <?php echo e($processo->status->label()); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">N/A</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-3">Cliente:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->cliente->nome ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Advogado:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->advogado->user->name ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Vara:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->vara->nome ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Especialidade:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->especialidade->nome ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Data de Abertura:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->data_abertura?->format('d/m/Y') ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Data da Petição:</dt>
                    <dd class="col-sm-9"><?php echo e($processo->data_peticao?->format('d/m/Y') ?? 'N/A'); ?></dd>

                    <?php if($processo->observacoes): ?>
                        <dt class="col-sm-3">Observações:</dt>
                        <dd class="col-sm-9"><?php echo e($processo->observacoes); ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas</h5>
            </div>
            <div class="card-body">
                <p><strong>Documentos:</strong> <?php echo e($processo->documentos->count()); ?></p>
                <p><strong>Audiências:</strong> <?php echo e($processo->audiencias->count()); ?></p>
                <p><strong>Prazos:</strong> <?php echo e($processo->prazos->count()); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/processos/show.blade.php ENDPATH**/ ?>