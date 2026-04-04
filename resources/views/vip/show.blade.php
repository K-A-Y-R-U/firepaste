@extends('layouts.app')

@section('title', $post->titulo)

@section('content')
<div class="container mb-5" style="min-height: calc(100vh - 200px);">
    <div class="row my-2 my-lg-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-2 p-md-3 paste-content">
                    <!-- Título responsive -->
                    <h3 class="mb-3 mb-md-4 text-dark fs-5 fs-md-4">{{ $post->titulo }}</h3>
                    
                    <!-- Tabs optimizados para móvil -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ url('/posts/' . $post->id) }}" 
                               class="nav-link fw-bold border px-3 py-2
                               {{ request()->is('posts/*') ? 'active bg-light text-dark border-bottom border-primary' : '' }}" 
                               aria-selected="{{ request()->is('posts/*') ? 'true' : 'false' }}">
                               {{ $post->pestana }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ url('/vip/' . $post->id) }}" 
                               class="nav-link fw-bold border px-3 py-2
                               {{ request()->is('vip/*') ? 'active bg-light text-dark border-bottom border-primary' : '' }}" 
                               aria-selected="{{ request()->is('vip/*') ? 'true' : 'false' }}">
                               👑 VIP
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Contenido con padding responsive -->
                    <div class="tab-content border border-tertiary rounded-bottom-2 border-top-0 p-0">
                        <div class="tab-pane show active p-2 p-md-3" id="tab_content1" role="tabpanel" aria-labelledby="tab_content1" tabindex="0">
                            <div class="content-wrapper">
                                {!! $post->contenido !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_content2" role="tabpanel" aria-labelledby="tab_content2" tabindex="0"></div>
                        <div class="tab-pane fade" id="tab_content3" role="tabpanel" aria-labelledby="tab_content3" tabindex="0"></div>
                    </div>
                    
                    <!-- Footer responsive sin columnas vacías -->
                    <div class="my-3 my-md-4 text-end">
                        <div class="visitas-box d-inline-flex align-items-center gap-2 text-dark">
                            <i class="bi bi-eye"></i> 
                            <span>Visitas: <strong>{{ $post->views }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos CSS adicionales para móviles -->
<style>
    /* Optimización general */
    @media (max-width: 768px) {
        .card {
            border-radius: 0.5rem;
        }
        
        .card-body {
            overflow-x: hidden;
        }
        
        /* Hacer el contenido más legible en móvil */
        .content-wrapper {
            font-size: 0.95rem;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
            width: 100%;
            overflow-x: auto;
        }
        
        /* Ajustar tablas - CRÍTICO para móviles */
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
        
        /* Estilo específico para la columna "Size" */
        .content-wrapper table th:last-child,
        .content-wrapper table td:last-child {
            width: 80px;
            text-align: right;
        }
        
        /* Ajustar imágenes */
        .content-wrapper img {
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Tabs sin scroll horizontal */
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            overflow: visible;
        }
        
        /* Botones más táctiles */
        .nav-link {
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Enlaces dentro del contenido */
        .content-wrapper a {
            word-break: break-word;
        }
        
        /* Código y pre formateado */
        .content-wrapper pre,
        .content-wrapper code {
            overflow-x: auto;
            font-size: 0.8rem;
        }
    }
    
    /* Para pantallas muy pequeñas */
    @media (max-width: 576px) {
        h3 {
            font-size: 1.1rem !important;
        }
        
        .content-wrapper {
            font-size: 0.9rem;
        }
        
        .content-wrapper table {
            font-size: 0.75rem;
        }
        
        .content-wrapper table th,
        .content-wrapper table td {
            padding: 0.4rem 0.2rem;
            font-size: 0.75rem;
        }
        
        .visitas-box {
            font-size: 0.85rem;
        }
    }
    
    /* Forzar que ningún elemento salga del contenedor */
    .paste-content * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Scroll horizontal visible solo para tablas */
    .content-wrapper table::-webkit-scrollbar {
        height: 6px;
    }
    
    .content-wrapper table::-webkit-scrollbar-thumb {
        background: #007bff;
        border-radius: 3px;
    }
    
    .content-wrapper table::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>
@endsection