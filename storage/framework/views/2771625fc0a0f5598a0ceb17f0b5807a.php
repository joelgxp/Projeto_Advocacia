

<?php $__env->startSection('title', 'Detalhes do Processo'); ?>
<?php $__env->startSection('page-title', 'Detalhes do Processo'); ?>

<?php $__env->startSection('content'); ?>
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Processo #<?php echo e($processo->id); ?></h5>
                <div>
                    <a href="<?php echo e(route('admin.processos.edit', $processo)); ?>" class="btn btn-sm btn-modern btn-modern-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="<?php echo e(route('admin.processos.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Número CNJ</p>
                        <p class="mb-0 fw-semibold"><?php echo e($processo->numero_cnj ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Status</p>
                        <p class="mb-0">
                            <span class="badge bg-info rounded-pill"><?php echo e($processo->status ?? 'N/A'); ?></span>
                        </p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Cliente</p>
                        <p class="mb-0 fw-semibold"><?php echo e($processo->cliente->nome ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Advogado</p>
                        <p class="mb-0 fw-semibold"><?php echo e($processo->advogado->user->name ?? 'N/A'); ?></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Vara</p>
                        <p class="mb-0 fw-semibold"><?php echo e($processo->vara->nome ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Especialidade</p>
                        <p class="mb-0 fw-semibold"><?php echo e($processo->especialidade->nome ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/processos/show.blade.php ENDPATH**/ ?>