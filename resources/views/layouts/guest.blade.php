<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Estilos -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/themify/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/theme.css') }}">
    @livewireStyles
</head>
<body>
    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center py-4">
        <div class="mb-4">
            <a href="/" wire:navigate>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 80px;">
            </a>
        </div>

        <div class="card w-100" style="max-width: 400px;">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/bootstrap.min.js') }}"></script>
    @livewireScripts
</body>
</html>