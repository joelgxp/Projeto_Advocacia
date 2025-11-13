<!-- Main Content -->
<div class="main-content-wrapper">
    <!-- Header -->
    <header class="modern-header">
        <div class="modern-header-content">
            <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
            <?php if (isset($usuario)): ?>
                <div class="user-menu">
                    <span><?php echo $usuario->nome; ?></span>
                </div>
            <?php endif; ?>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Page Content -->
    <div class="content-area">
        <?php if (isset($view)): ?>
            <?php $this->load->view($view, isset($data) ? $data : array()); ?>
        <?php endif; ?>
    </div>
</div>

