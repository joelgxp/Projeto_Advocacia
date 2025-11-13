<div class="modern-header">
    <div class="modern-header-content">
        <div>
            <h1 class="mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
        </div>
        <?php if(auth()->check()): ?>
        <div class="dropdown">
            <button class="user-menu-btn dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
                <span><?php echo e(auth()->user()->name ?? auth()->user()->email); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userMenu" style="border-radius: 0.75rem; border: 1px solid var(--border-color);">
                <li><h6 class="dropdown-header"><?php echo e(auth()->user()->email); ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item" style="border-radius: 0;">
                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/layouts/partials/header.blade.php ENDPATH**/ ?>