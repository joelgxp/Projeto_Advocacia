<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'Login - ' . config('app.name')); ?></title>
    
    <!-- Fonts -->
    <link href="<?php echo e(asset('css/vendor/inter-font.css')); ?>" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo e(asset('css/vendor/bootstrap.min.css')); ?>" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="<?php echo e(asset('css/vendor/fontawesome.min.css')); ?>" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    
    <!-- jQuery (deve vir antes do Bootstrap) -->
    <script src="<?php echo e(asset('js/vendor/jquery.min.js')); ?>"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="<?php echo e(asset('js/vendor/bootstrap.bundle.min.js')); ?>"></script>
    
    <!-- Custom Scripts -->
    <script src="<?php echo e(asset('js/script.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>



<?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/layouts/guest.blade.php ENDPATH**/ ?>