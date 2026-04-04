@use(Attargah\AdminBar\View\AdminBarRenderHook)

@if(auth('admin')->check())

@php
    $postId = null;
    $route = request()->route();
    if ($route && $route->getName() === 'posts.show') {
        $postId = $route->parameter('id');
    }
@endphp

<style>
    #fp-admin-bar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 32px;
        background: #1d2327;
        color: #c3c4c7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 8px;
        z-index: 99999;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 13px;
        line-height: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.4);
    }
    #fp-admin-bar a {
        color: #c3c4c7;
        text-decoration: none;
        transition: color 0.15s;
    }
    #fp-admin-bar a:hover { color: #fff; }
    #fp-admin-bar .fp-bar-left {
        display: flex;
        align-items: center;
        gap: 4px;
        overflow: hidden;
    }
    #fp-admin-bar .fp-bar-right {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }
    .fp-bar-item {
        display: flex;
        align-items: center;
        height: 32px;
        padding: 0 8px;
        cursor: pointer;
        white-space: nowrap;
        position: relative;
    }
    .fp-bar-item:hover { background: #2c3338; color: #fff; }
    .fp-bar-item svg { margin-right: 4px; flex-shrink: 0; }
    .fp-bar-item .fp-sub-menu {
        display: none;
        position: absolute;
        top: 32px;
        left: 0;
        min-width: 180px;
        background: #1d2327;
        border: 1px solid #2c3338;
        box-shadow: 0 3px 8px rgba(0,0,0,0.4);
        z-index: 100000;
    }
    .fp-bar-item:hover .fp-sub-menu { display: block; }
    .fp-sub-menu a {
        display: block;
        padding: 8px 12px;
        color: #c3c4c7 !important;
        font-size: 13px;
        border-bottom: 1px solid #2c3338;
    }
    .fp-sub-menu a:last-child { border-bottom: none; }
    .fp-sub-menu a:hover { background: #2c3338; color: #fff !important; }
    .fp-user-item .fp-sub-menu { left: auto; right: 0; }
    .fp-divider {
        width: 1px;
        height: 16px;
        background: #3c434a;
        margin: 0 4px;
        flex-shrink: 0;
    }
    .fp-edit-btn {
        display: flex;
        align-items: center;
        height: 32px;
        padding: 0 10px;
        background: #f59e0b;
        color: #000 !important;
        font-weight: 600;
        font-size: 12px;
        gap: 4px;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .fp-edit-btn:hover { background: #d97706; color: #000 !important; }
    .fp-edit-btn svg { margin-right: 0; }

    /* Etiquetas de texto — se ocultan en móvil */
    .fp-label { display: inline; }

    /* Menú hamburguesa — solo móvil */
    #fp-mobile-toggle {
        display: none;
        background: none;
        border: none;
        color: #c3c4c7;
        cursor: pointer;
        padding: 0 8px;
        height: 32px;
        align-items: center;
    }
    #fp-mobile-menu {
        display: none;
        position: fixed;
        top: 32px;
        left: 0;
        right: 0;
        background: #1d2327;
        border-top: 1px solid #2c3338;
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        z-index: 99998;
        flex-direction: column;
    }
    #fp-mobile-menu.open { display: flex; }
    #fp-mobile-menu a,
    #fp-mobile-menu button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        color: #c3c4c7;
        text-decoration: none;
        font-size: 14px;
        border-bottom: 1px solid #2c3338;
        background: none;
        border-left: none;
        border-right: none;
        border-top: none;
        width: 100%;
        text-align: left;
        font-family: inherit;
        cursor: pointer;
    }
    #fp-mobile-menu a:hover,
    #fp-mobile-menu button:hover { background: #2c3338; color: #fff; }
    #fp-mobile-menu .fp-mobile-edit {
        background: #f59e0b !important;
        color: #000 !important;
        font-weight: 600;
    }
    #fp-mobile-menu .fp-mobile-edit:hover { background: #d97706 !important; }

    #fp-admin-bar-spacer { height: 32px; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .fp-desktop-only { display: none !important; }
        #fp-mobile-toggle { display: flex; }
        .fp-bar-item.fp-logo-item { padding: 0 6px; }
        .fp-edit-btn .fp-label { display: none; }
        .fp-edit-btn { padding: 0 8px; }
    }
    @media (min-width: 769px) {
        #fp-mobile-toggle { display: none !important; }
        #fp-mobile-menu { display: none !important; }
    }
</style>

<div id="fp-admin-bar">
    {{-- Lado izquierdo --}}
    <div class="fp-bar-left">

        {{-- Logo --}}
        <div class="fp-bar-item fp-logo-item">
            <a href="{{ route('filament.admin.pages.dashboard') }}" style="display:flex;align-items:center;gap:6px;font-weight:600;color:#fff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Firepaste
            </a>
        </div>

        <div class="fp-divider fp-desktop-only"></div>

        {{-- Dashboard --}}
        <div class="fp-bar-item fp-desktop-only">
            <a href="{{ route('filament.admin.pages.dashboard') }}" style="display:flex;align-items:center;gap:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="fp-label">Dashboard</span>
            </a>
        </div>

        {{-- Usuarios --}}
        <div class="fp-bar-item fp-desktop-only">
            <a href="{{ route('filament.admin.resources.users.index') }}" style="display:flex;align-items:center;gap:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="fp-label">Usuarios</span>
            </a>
        </div>

        {{-- Posts --}}
        <div class="fp-bar-item fp-desktop-only">
            <a href="{{ route('filament.admin.resources.posts.index') }}" style="display:flex;align-items:center;gap:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="fp-label">Posts</span>
            </a>
        </div>

        {{-- Gift Codes --}}
        <div class="fp-bar-item fp-desktop-only">
            <a href="{{ route('filament.admin.resources.gift-codes.index') }}" style="display:flex;align-items:center;gap:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
                <span class="fp-label">Gift Codes</span>
            </a>
        </div>

        {{-- Botón Editar Post --}}
        @if($postId)
            <div class="fp-divider"></div>
            <a href="{{ route('filament.admin.resources.posts.edit', ['record' => $postId]) }}" class="fp-bar-item fp-edit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="fp-label">Editar post</span>
            </a>
        @endif

    </div>

    {{-- Lado derecho --}}
    <div class="fp-bar-right">

        {{-- Hamburguesa móvil --}}
        <button id="fp-mobile-toggle" onclick="document.getElementById('fp-mobile-menu').classList.toggle('open')" aria-label="Menú">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Usuario con dropdown (desktop) --}}
        <div class="fp-bar-item fp-user-item fp-desktop-only">
            <span style="display:flex;align-items:center;gap:6px;color:#fff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#c3c4c7" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
                {{ ucwords(auth('admin')->user()->name) }}
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
            <div class="fp-sub-menu">
                <a href="{{ route('filament.admin.pages.dashboard') }}">Panel Admin</a>
                <form action="{{ route('filament.admin.auth.logout') }}" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" style="width:100%;text-align:left;padding:8px 12px;background:none;border:none;color:#c3c4c7;font-size:13px;cursor:pointer;font-family:inherit;">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Menú móvil desplegable --}}
<div id="fp-mobile-menu">
    <a href="{{ route('filament.admin.pages.dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('filament.admin.resources.users.index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        Usuarios
    </a>
    <a href="{{ route('filament.admin.resources.posts.index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Posts
    </a>
    <a href="{{ route('filament.admin.resources.gift-codes.index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
        Gift Codes
    </a>
    @if($postId)
        <a href="{{ route('filament.admin.resources.posts.edit', ['record' => $postId]) }}" class="fp-mobile-edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Editar post
        </a>
    @endif
    <form action="{{ route('filament.admin.auth.logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Cerrar sesión
        </button>
    </form>
</div>

<div id="fp-admin-bar-spacer"></div>

<script>
    // Cerrar menú móvil al hacer click fuera
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('fp-mobile-menu');
        const toggle = document.getElementById('fp-mobile-toggle');
        if (menu && toggle && !menu.contains(e.target) && !toggle.contains(e.target)) {
            menu.classList.remove('open');
        }
    });
</script>

@endif