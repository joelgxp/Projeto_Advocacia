

<?php $__env->startSection('title', 'Processos'); ?>
<?php $__env->startSection('page-title', 'Processos'); ?>

<?php $__env->startSection('content'); ?>
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Lista de Processos</h5>
                <a href="<?php echo e(route('admin.processos.create')); ?>" class="btn btn-modern btn-modern-primary">
                    <i class="fas fa-plus me-2"></i> Novo Processo
                </a>
            </div>
            <div class="modern-card-body">
                <?php if($processos->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Advogado</th>
                                <th>Vara</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $processos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $processo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($processo->numero_processo ?? '-'); ?></td>
                                <td><?php echo e($processo->cliente->nome ?? '-'); ?></td>
                                <td><?php echo e($processo->advogado->user->name ?? '-'); ?></td>
                                <td><?php echo e($processo->vara->nome ?? '-'); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($processo->status ?? '-'); ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.processos.show', $processo)); ?>" class="btn btn-sm btn-modern btn-modern-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.processos.edit', $processo)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($processos->links()); ?>

                </div>
                <?php else: ?>
                <p class="text-muted text-center py-4">Nenhum processo encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/processos/index.blade.php ENDPATH**/ ?>