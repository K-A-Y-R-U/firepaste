@extends('layouts.app')

@section('title', $post->titulo)

@section('content')
<div class="container mb-5" style="min-height: calc(100vh - 200px);">
    <div class="row my-2 my-lg-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-2 p-md-3 paste-content">
                    <h3 class="mb-3 mb-md-4 text-dark fs-5 fs-md-4">{{ $post->titulo }}</h3>
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ url('/posts/' . $post->id) }}" 
                               class="nav-link fw-bold border px-3 py-2 {{ request()->is('posts/*') ? 'tab-active' : '' }}" 
                               aria-selected="{{ request()->is('posts/*') ? 'true' : 'false' }}">
                               {{ $post->pestana }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ url('/vip/' . $post->id) }}" 
                               class="nav-link fw-bold border px-3 py-2 {{ request()->is('vip/*') ? 'tab-active' : '' }}" 
                               aria-selected="{{ request()->is('vip/*') ? 'true' : 'false' }}">
                               👑 VIP
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content border border-tertiary rounded-bottom-2 border-top-0 p-0">
                        <div class="tab-pane show active p-2 p-md-3" id="tab_content1" role="tabpanel" tabindex="0">
                            <div class="content-wrapper">
                                {!! $post->contenido !!}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Footer: botón reportar izquierda + visitas derecha --}}
                    <div class="my-3 my-md-4 d-flex align-items-center justify-content-between gap-3">
                        <livewire:report-post :postId="$post->id" :key="'report-'.$post->id" />
                        <div class="visitas-box d-inline-flex align-items-center gap-2 text-dark">
                            <i class="bi bi-eye"></i> 
                            <span>{{ __("Visitas:") }} <strong>{{ $post->views }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-link.tab-active {
        background: #f8f9fa !important;
        color: #212529 !important;
        border-bottom: 2px solid var(--theme-color) !important;
    }
    .nav-tabs .nav-link.tab-active,
    .nav-tabs .nav-link.tab-active:hover,
    .nav-tabs .nav-link.tab-active:focus {
        border-bottom-color: var(--theme-color) !important;
        border-bottom-width: 2px !important;
    }
    .content-wrapper table::-webkit-scrollbar { height: 6px; }
    .content-wrapper table::-webkit-scrollbar-thumb {
        background: var(--theme-color);
        border-radius: 3px;
    }
    .content-wrapper table::-webkit-scrollbar-track { background: #f1f1f1; }
    .visitas-box {
        border-color: var(--theme-color) !important;
        transition: all 0.2s ease;
    }
    @media (max-width: 768px) {
        .card { border-radius: 0.5rem; }
        .card-body { overflow-x: hidden; }
        .content-wrapper {
            font-size: 0.95rem;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
            width: 100%;
            overflow-x: auto;
        }
        .content-wrapper table {
            width: 100%;
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            font-size: 0.85rem;
            margin: 0;
        }
        .content-wrapper table thead,
        .content-wrapper table tbody,
        .content-wrapper table tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .content-wrapper table th,
        .content-wrapper table td {
            padding: 0.5rem 0.3rem;
            font-size: 0.8rem;
            word-break: break-word;
        }
        .content-wrapper table th:last-child,
        .content-wrapper table td:last-child {
            width: 80px;
            text-align: right;
        }
        .content-wrapper img { max-width: 100% !important; height: auto !important; }
        .nav-tabs { border-bottom: 1px solid #dee2e6; overflow: visible; }
        .nav-link { min-height: 44px; display: flex; align-items: center; justify-content: center; }
        .content-wrapper a { word-break: break-word; }
        .content-wrapper pre,
        .content-wrapper code { overflow-x: auto; font-size: 0.8rem; }
    }
    @media (max-width: 576px) {
        h3 { font-size: 1.1rem !important; }
        .content-wrapper { font-size: 0.9rem; }
        .content-wrapper table { font-size: 0.75rem; }
        .content-wrapper table th,
        .content-wrapper table td { padding: 0.4rem 0.2rem; font-size: 0.75rem; }
        .visitas-box { font-size: 0.85rem; }
    }
    .paste-content * { max-width: 100%; box-sizing: border-box; }
</style>
@endsection

@section('scripts')
@if(!empty($moreConfigs['url_shortener_enabled']) && !empty($moreConfigs['url_shortener_api_full']))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const proxyUrl = "{{ route('api.shorten') }}?url=";
        const siteHost = window.location.hostname;
        const links    = document.querySelectorAll('.paste-content a[href^="http"]');

        links.forEach(link => {
            const originalUrl = link.href;

            if (originalUrl.includes(siteHost)) return;

            // ✅ Usar .text() y limpiar scripts inyectados antes de parsear JSON
            fetch(proxyUrl + encodeURIComponent(originalUrl))
                .then(r => r.text())
                .then(text => {
                    const clean = text.replace(/<script[^>]*>.*?<\/script>/gis, '').trim();
                    const data = JSON.parse(clean);
                    if (data.status === 'success' && data.shortenedUrl) {
                        link.href = data.shortenedUrl;
                    }
                })
                .catch(() => {});

            link.addEventListener('click', e => {
                e.preventDefault();
                window.open(link.href, '_blank');
            });
        });
    });
</script>
@endif
@endsection