<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Estilos -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/themify/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    {{-- ✅ Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- ✅ Flag Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/theme.css') }}">

    {{-- ✅ Theme Color dinámico desde General Settings --}}
    @php
        $settings = \App\Models\GeneralSetting::first();
        $themeColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $settings?->theme_color ?? '')
            ? $settings->theme_color
            : '#1e71bb';
    @endphp
    <style>
        :root {
            --theme-color: {{ $themeColor }};
        }

        /* ── Botones principales ── */
        .btn-main, .btn-small, .btn-transparent {
            background: var(--theme-color) !important;
            color: #fff !important;
            border-color: var(--theme-color) !important;
        }
        .btn-main:hover, .btn-small:hover, .btn-transparent:hover {
            filter: brightness(0.88);
            color: #fff !important;
        }

        /* ── Botón Membresías ── */
        .btn-solid-border {
            border: 2px solid var(--theme-color) !important;
            color: #fff !important;
            background: transparent !important;
            padding: 8px 22px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.25s ease;
            display: inline-block;
        }
        .btn-solid-border:hover {
            background: var(--theme-color) !important;
            border-color: var(--theme-color) !important;
            color: #fff !important;
        }

        /* ── Header y Footer border ── */
        header {
            border-bottom: 3px solid var(--theme-color) !important;
        }
        .footer-dark {
            border-top: 3px solid var(--theme-color) !important;
        }

        /* ── Logo navbar-brand: hover con color del tema ── */
        #navbar .navbar-brand:hover,
        #navbar .navbar-brand:focus {
            color: var(--theme-color) !important;
        }

        /* ── Nav links hover con color del tema ── */
        #navbar .nav-link:hover,
        #navbar .nav-link:focus,
        #navbar .active .nav-link {
            color: var(--theme-color) !important;
        }

        /* ── Títulos de posts: solo hover, NO color permanente ── */
        .card-sky {
            color: inherit !important; /* ✅ Vuelve al color original */
        }
        .paste a h6:hover,
        .paste a h6:focus,
        a:hover .card-sky,
        a:focus .card-sky {
            color: var(--theme-color) !important;
        }

        /* ── Ícono de vistas y número ── */
        .post-views svg {
            color: var(--theme-color) !important;
        }

        /* ── Íconos de filtros (Ordenar por / Filtrar por categoría) ── */
        .filter-label i {
            color: var(--theme-color) !important;
        }

        /* ── Dropdown arrow ── */
        .dropdown-arrow {
            color: var(--theme-color) !important;
        }

        /* ── Paginación custom (search-posts) ── */
        .page-btn:hover:not(.disabled):not(.active):not(.dots) {
            border-color: var(--theme-color) !important;
            color: var(--theme-color) !important;
            background: color-mix(in srgb, var(--theme-color) 10%, white) !important;
        }
        .page-btn.active {
            background: var(--theme-color) !important;
            border-color: var(--theme-color) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px color-mix(in srgb, var(--theme-color) 40%, transparent) !important;
        }

        /* ── Paginación Bootstrap (posts/index) ── */
        .page-item.active .page-link {
            background-color: var(--theme-color) !important;
            border-color: var(--theme-color) !important;
        }
        .page-link {
            color: var(--theme-color) !important;
        }
        .page-link:hover {
            color: var(--theme-color) !important;
        }

        /* ── Filtros dropdown border focus ── */
        .dropdown-toggle-custom:hover,
        .dropdown-toggle-custom:focus {
            border-color: var(--theme-color) !important;
            box-shadow: 0 2px 8px color-mix(in srgb, var(--theme-color) 15%, transparent) !important;
        }
        .dropdown-custom.open .dropdown-toggle-custom {
            border-color: var(--theme-color) !important;
        }
        .dropdown-menu-custom {
            border-color: var(--theme-color) !important;
        }
        .dropdown-item-custom.active {
            background: color-mix(in srgb, var(--theme-color) 12%, white) !important;
            color: var(--theme-color) !important;
            border-left: 4px solid var(--theme-color) !important;
        }
        .dropdown-item-custom.active span {
            color: var(--theme-color) !important;
        }
        .dropdown-item-custom i:first-child {
            color: var(--theme-color) !important;
        }
        .dropdown-menu-custom::-webkit-scrollbar-thumb {
            background: var(--theme-color) !important;
        }

        /* ── Card hover border ── */
        .card-hoverable:hover {
            border-color: color-mix(in srgb, var(--theme-color) 40%, #dee2e6) !important;
            box-shadow: 0 6px 20px color-mix(in srgb, var(--theme-color) 15%, transparent) !important;
        }

        /* ── Badge de catálogo: estilo original restaurado ── */
        .post-catalog-badge {
            color: #667eea !important;
            background: #eef0fd !important;
            border: 1.5px solid #b3bcf5 !important;
        }
        .post-catalog-badge i {
            color: #667eea !important;
        }
        .post-catalog-badge:hover {
            background: #dde1fb !important;
        }

        /* ── Search button ── */
        .search-btn {
            background: var(--theme-color) !important;
            border-color: var(--theme-color) !important;
        }
    </style>

    @livewireStyles
</head>
<body>
    {{-- ✅ Admin Bar — solo visible para admins logueados --}}
    <x-admin-bar-admin-bar />

    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Scripts globales -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/bootstrap.min.js') }}"></script>
    @livewireScripts
    @yield('scripts')
</body>
</html>