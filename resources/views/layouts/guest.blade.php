<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Login - ' . config('app.name'))</title>
    
    <!-- Fonts -->
    <link href="{{ asset('css/vendor/inter-font.css') }}" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset('css/vendor/fontawesome.min.css') }}" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- jQuery (deve vir antes do Bootstrap) -->
    <script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>



