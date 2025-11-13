<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ config('app.url') }}">
    
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Fonts -->
    <link href="{{ asset('css/vendor/inter-font.css') }}" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset('css/vendor/fontawesome.min.css') }}" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="{{ asset('css/style-painel.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="modern-layout">
    <div class="app-wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')
        
        <!-- Main Content -->
        <div class="main-content-wrapper">
            <!-- Header -->
            @include('layouts.partials.header')
            
            <!-- Flash Messages -->
            @include('layouts.partials.flash-messages')
            
            <!-- Page Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
    
    <!-- jQuery (deve vir antes do Bootstrap) -->
    <script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/mascaras.js') }}"></script>
    @stack('scripts')
</body>
</html>



