

<?php $__env->startSection('title', 'Clientes'); ?>
<?php $__env->startSection('page-title', 'Clientes'); ?>

<?php $__env->startSection('content'); ?>
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-gradient">Lista de Clientes</h5>
                <a href="<?php echo e(route('admin.clientes.create')); ?>" class="btn btn-modern btn-modern-primary">
                    <i class="fas fa-plus me-2"></i> Novo Cliente
                </a>
            </div>
            <div class="modern-card-body">
                <?php if($clientes->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF/CNPJ</th>
                                <th>Tipo</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($cliente->nome); ?></td>
                                <td><?php echo e($cliente->cpf_cnpj); ?></td>
                                <td><span class="badge bg-info"><?php echo e($cliente->tipo_pessoa); ?></span></td>
                                <td><?php echo e($cliente->email ?? '-'); ?></td>
                                <td><?php echo e($cliente->telefone ?? $cliente->celular ?? '-'); ?></td>
                                <td>
                                    <span class="badge <?php echo e($cliente->ativo ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($cliente->ativo ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.clientes.show', $cliente)); ?>" class="btn btn-sm btn-modern btn-modern-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.clientes.edit', $cliente)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($clientes->links()); ?>

                </div>
                <?php else: ?>
                <p class="text-muted text-center py-4">Nenhum cliente encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/clientes/index.blade.php ENDPATH**/ ?>