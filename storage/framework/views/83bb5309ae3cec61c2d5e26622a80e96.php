

<?php $__env->startSection('title', 'Editar Cliente'); ?>
<?php $__env->startSection('page-title', 'Editar Cliente'); ?>

<?php $__env->startSection('content'); ?>
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Editar Cliente</h5>
            </div>
            <div class="modern-card-body">
                <form action="<?php echo e(route('admin.clientes.update', $cliente)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Nome *</label>
                            <input type="text" name="nome" class="form-control form-control-modern" value="<?php echo e($cliente->nome); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">CPF/CNPJ *</label>
                            <input type="text" name="cpf_cnpj" class="form-control form-control-modern" value="<?php echo e($cliente->cpf_cnpj); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Tipo de Pessoa *</label>
                            <select name="tipo_pessoa" class="form-control form-control-modern" required>
                                <option value="PF" <?php echo e($cliente->tipo_pessoa == 'PF' ? 'selected' : ''); ?>>Pessoa Física</option>
                                <option value="PJ" <?php echo e($cliente->tipo_pessoa == 'PJ' ? 'selected' : ''); ?>>Pessoa Jurídica</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">E-mail</label>
                            <input type="email" name="email" class="form-control form-control-modern" value="<?php echo e($cliente->email); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Telefone</label>
                            <input type="text" name="telefone" class="form-control form-control-modern" value="<?php echo e($cliente->telefone); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Celular</label>
                            <input type="text" name="celular" class="form-control form-control-modern" value="<?php echo e($cliente->celular); ?>">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-modern btn-modern-primary">
                            <i class="fas fa-save me-2"></i> Atualizar
                        </button>
                        <a href="<?php echo e(route('admin.clientes.index')); ?>" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/clientes/edit.blade.php ENDPATH**/ ?>