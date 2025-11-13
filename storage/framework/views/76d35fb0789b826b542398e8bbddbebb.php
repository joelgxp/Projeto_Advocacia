<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="app-url" content="<?php echo e(config('app.url')); ?>">
    
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    
    <!-- Fonts -->
    <link href="<?php echo e(asset('css/vendor/inter-font.css')); ?>" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo e(asset('css/vendor/bootstrap.min.css')); ?>" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="<?php echo e(asset('css/vendor/fontawesome.min.css')); ?>" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="<?php echo e(asset('css/style-painel.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="modern-layout">
    <div class="app-wrapper">
        <!-- Sidebar -->
        <?php echo $__env->make('layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Main Content -->
        <div class="main-content-wrapper">
            <!-- Header -->
            <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Flash Messages -->
            <?php echo $__env->make('layouts.partials.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Page Content -->
            <div class="content-area">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
    
    <!-- jQuery (deve vir antes do Bootstrap) -->
    <script src="<?php echo e(asset('js/vendor/jquery.min.js')); ?>"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="<?php echo e(asset('js/vendor/bootstrap.bundle.min.js')); ?>"></script>
    
    <!-- Custom Scripts -->
    <script src="<?php echo e(asset('js/script.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mascaras.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>



<?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/layouts/app.blade.php ENDPATH**/ ?>