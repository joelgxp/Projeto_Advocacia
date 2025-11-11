<aside class="col-md-3 col-lg-2 sidebar p-0">
    <div class="position-sticky pt-3">
        <div class="text-center p-3 border-bottom">
            <h4 class="text-white mb-0">
                <i class="fas fa-gavel me-2"></i>
                <?php echo e(config('app.name')); ?>

            </h4>
        </div>
        
        <nav class="nav flex-column p-3">
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->hasRole('admin')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.processos.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.processos.index')); ?>">
                        <i class="fas fa-folder-open me-2"></i> Processos
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.clientes.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.clientes.index')); ?>">
                        <i class="fas fa-users me-2"></i> Clientes
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.advogados.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.advogados.index')); ?>">
                        <i class="fas fa-user-tie me-2"></i> Advogados
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.funcionarios.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.funcionarios.index')); ?>">
                        <i class="fas fa-user-friends me-2"></i> Funcionários
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.consulta-processual.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.consulta-processual.index')); ?>">
                        <i class="fas fa-search me-2"></i> Consulta Processual
                    </a>
                <?php elseif(auth()->user()->hasRole('advogado')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('advogado.*') ? 'active' : ''); ?>" href="<?php echo e(route('advogado.dashboard')); ?>">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('advogado.processos.*') ? 'active' : ''); ?>" href="<?php echo e(route('advogado.processos.index')); ?>">
                        <i class="fas fa-folder-open me-2"></i> Processos
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('advogado.clientes.*') ? 'active' : ''); ?>" href="<?php echo e(route('advogado.clientes.index')); ?>">
                        <i class="fas fa-users me-2"></i> Clientes
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('advogado.audiencias.*') ? 'active' : ''); ?>" href="<?php echo e(route('advogado.audiencias.index')); ?>">
                        <i class="fas fa-calendar-check me-2"></i> Audiências
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('advogado.agenda.*') ? 'active' : ''); ?>" href="<?php echo e(route('advogado.agenda.index')); ?>">
                        <i class="fas fa-calendar me-2"></i> Agenda
                    </a>
                <?php elseif(auth()->user()->hasAnyRole(['recepcionista', 'tesoureiro'])): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('recepcao.*') ? 'active' : ''); ?>" href="<?php echo e(route('recepcao.dashboard')); ?>">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('recepcao.processos.*') ? 'active' : ''); ?>" href="<?php echo e(route('recepcao.processos.index')); ?>">
                        <i class="fas fa-folder-open me-2"></i> Processos
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('recepcao.clientes.*') ? 'active' : ''); ?>" href="<?php echo e(route('recepcao.clientes.index')); ?>">
                        <i class="fas fa-users me-2"></i> Clientes
                    </a>
                    <?php if(auth()->user()->hasRole('tesoureiro')): ?>
                        <a class="nav-link <?php echo e(request()->routeIs('recepcao.receber.*') ? 'active' : ''); ?>" href="<?php echo e(route('recepcao.receber.index')); ?>">
                            <i class="fas fa-money-bill-wave me-2"></i> Contas a Receber
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('recepcao.pagar.*') ? 'active' : ''); ?>" href="<?php echo e(route('recepcao.pagar.index')); ?>">
                            <i class="fas fa-credit-card me-2"></i> Contas a Pagar
                        </a>
                    <?php endif; ?>
                <?php elseif(auth()->user()->hasRole('cliente')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('cliente.*') ? 'active' : ''); ?>" href="<?php echo e(route('cliente.dashboard')); ?>">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('cliente.processos.*') ? 'active' : ''); ?>" href="<?php echo e(route('cliente.processos.index')); ?>">
                        <i class="fas fa-folder-open me-2"></i> Meus Processos
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('cliente.documentos.*') ? 'active' : ''); ?>" href="<?php echo e(route('cliente.documentos.index')); ?>">
                        <i class="fas fa-file me-2"></i> Documentos
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('cliente.comunicacoes.*') ? 'active' : ''); ?>" href="<?php echo e(route('cliente.comunicacoes.index')); ?>">
                        <i class="fas fa-comments me-2"></i> Comunicações
                    </a>
                <?php endif; ?>
                
                <hr class="my-3 text-white-50">
                
                <a class="nav-link" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            <?php endif; ?>
        </nav>
    </div>
</aside>

<?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>