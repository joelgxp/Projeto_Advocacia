<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h4>Sistema de Advocacia</h4>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <?php if (isset($usuario)): ?>
                <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                
                <?php if ($this->permission->canView($usuario->permissoes_id, 'processos')): ?>
                    <li><a href="<?php echo base_url('processos'); ?>"><i class="fas fa-file-alt"></i> Processos</a></li>
                <?php endif; ?>
                
                <?php if ($this->permission->canView($usuario->permissoes_id, 'clientes')): ?>
                    <li><a href="<?php echo base_url('clientes'); ?>"><i class="fas fa-users"></i> Clientes</a></li>
                <?php endif; ?>
                
                <li><a href="<?php echo base_url('login/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>

