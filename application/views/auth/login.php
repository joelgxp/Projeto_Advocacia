<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Login - Sistema de Advocacia'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo base_url('assets/css/vendor/bootstrap.min.css'); ?>" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/css/vendor/fontawesome.min.css'); ?>" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-gavel text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-2">Sistema de Advocacia</h2>
                            <p class="text-muted">Faça login para acessar o sistema</p>
                        </div>

                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo base_url('login/processar'); ?>">
                            <div class="mb-4">
                                <label for="usuario" class="form-label">Usuário/E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-start-0" 
                                           id="usuario" 
                                           name="usuario" 
                                           required 
                                           autofocus
                                           placeholder="Seu usuário ou e-mail">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0" 
                                           id="senha" 
                                           name="senha" 
                                           required
                                           placeholder="Sua senha">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Entrar
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Sistema de Gerenciamento de Advocacia xxx
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/vendor/jquery.min.js'); ?>"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="<?php echo base_url('assets/js/vendor/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>

