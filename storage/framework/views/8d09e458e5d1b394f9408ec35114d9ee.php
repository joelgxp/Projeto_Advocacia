

<?php $__env->startSection('title', 'Clientes - Administrador'); ?>

<?php $__env->startSection('page-title', 'Clientes'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <a href="<?php echo e(route('admin.clientes.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Cliente
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lista de Clientes</h5>
    </div>
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($clientes->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF/CNPJ</th>
                            <th>Tipo</th>
                            <th>Email</th>
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
                                <td>
                                    <span class="badge bg-<?php echo e($cliente->tipo_pessoa == 'PF' ? 'info' : 'primary'); ?>">
                                        <?php echo e($cliente->tipo_pessoa); ?>

                                    </span>
                                </td>
                                <td><?php echo e($cliente->email ?? 'N/A'); ?></td>
                                <td><?php echo e($cliente->telefone ?? $cliente->celular ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($cliente->ativo ? 'success' : 'secondary'); ?>">
                                        <?php echo e($cliente->ativo ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.clientes.show', $cliente->id)); ?>" 
                                           class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.clientes.edit', $cliente->id)); ?>" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.clientes.destroy', $cliente->id)); ?>" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Nenhum cliente encontrado.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/clientes/index.blade.php ENDPATH**/ ?>