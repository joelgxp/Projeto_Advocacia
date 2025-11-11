<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="app-url" content="<?php echo e(config('app.url')); ?>">
    
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php echo $__env->make('layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                
                <!-- Flash Messages -->
                <?php echo $__env->make('layouts.partials.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                
                <!-- Page Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container"></div>
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>


<?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/layouts/app.blade.php ENDPATH**/ ?>