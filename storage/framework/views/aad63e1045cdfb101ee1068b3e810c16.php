<header class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
    <h1 class="h3 mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
    
    <?php if(auth()->guard()->check()): ?>
        <div class="d-flex align-items-center">
            <!-- Notificações -->
            <div class="dropdown me-3">
                <button class="btn btn-link text-dark position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fa-lg"></i>
                    <?php if(auth()->user()->notificacoes()->where('lida', false)->count() > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo e(auth()->user()->notificacoes()->where('lida', false)->count()); ?>

                        </span>
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notificações</h6></li>
                    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notificacoes()->latest()->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li>
                            <a class="dropdown-item <?php echo e(!$notificacao->lida ? 'bg-light' : ''); ?>" href="<?php echo e($notificacao->link ?? '#'); ?>">
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="fw-bold"><?php echo e($notificacao->titulo); ?></small>
                                    <small class="text-muted"><?php echo e($notificacao->created_at->diffForHumans()); ?></small>
                                </div>
                                <small class="text-muted"><?php echo e(Str::limit($notificacao->mensagem, 50)); ?></small>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li><span class="dropdown-item text-muted">Nenhuma notificação</span></li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                </ul>
            </div>
            
            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fa-lg me-2"></i>
                    <?php echo e(auth()->user()->name); ?>

                    <i class="fas fa-chevron-down ms-2 small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header"><?php echo e(auth()->user()->roles->first()->name ?? 'Usuário'); ?></h6></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Meu Perfil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                        </a>
                        <form id="logout-form-header" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</header>

<?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/layouts/partials/header.blade.php ENDPATH**/ ?>