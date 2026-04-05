@extends('layouts.app')

@section('content')
<div class="container mb-5" style="min-height: calc(100vh - 200px);">
    <div class="row my-4 my-lg-5 justify-content-center">
        <!-- Buscador -->
        <div class="col-md-8 mt-1">
            <form method="get">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control search-input" value="{{ request('search') }}" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="button-addon2">
                    <button type="submit" class="btn btn-main search-btn" id="button-addon2">Buscar</button>
                </div>
            </form>
        </div>

        <!-- Filtros -->
        <div class="col-md-8 mb-4">
            <div class="row g-3">
                <!-- Ordenar por -->
                <div class="col-md-6">
                    <label class="filter-label mb-2">
                        <i class="bi bi-sort-down me-2"></i>Ordenar por
                    </label>
                    <div class="dropdown-custom">
                        <button type="button" class="dropdown-toggle-custom" id="sortDropdown">
                            <span class="dropdown-value">
                                @if(request('sort') == 'newest')
                                    Lo nuevo
                                @elseif(request('sort') == 'most_viewed')
                                    Lo más visto
                                @else
                                    Más reciente
                                @endif
                            </span>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="dropdown-menu-custom" id="sortMenu">
                            <a href="{{ route('posts.index', ['sort' => 'newest', 'catalog' => request('catalog'), 'search' => request('search')]) }}" 
                               class="dropdown-item-custom {{ request('sort') == 'newest' || !request('sort') ? 'active' : '' }}">
                                <i class="bi bi-clock-fill"></i>
                                <span>Lo nuevo</span>
                                @if(request('sort') == 'newest' || !request('sort'))
                                    <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                                @endif
                            </a>
                            <a href="{{ route('posts.index', ['sort' => 'most_viewed', 'catalog' => request('catalog'), 'search' => request('search')]) }}" 
                               class="dropdown-item-custom {{ request('sort') == 'most_viewed' ? 'active' : '' }}">
                                <i class="bi bi-eye-fill"></i>
                                <span>Lo más visto</span>
                                @if(request('sort') == 'most_viewed')
                                    <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtrar por categoría -->
                <div class="col-md-6">
                    <label class="filter-label mb-2">
                        <i class="bi bi-funnel-fill me-2"></i>Filtrar por categoría
                    </label>
                    <div class="dropdown-custom">
                        <button type="button" class="dropdown-toggle-custom" id="categoryDropdown">
                            <span class="dropdown-value">
                                @if(request('catalog'))
                                    {{ $catalogs->where('slug', request('catalog'))->first()->nombre ?? 'Todas las categorías' }}
                                @else
                                    Todas las categorías
                                @endif
                            </span>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="dropdown-menu-custom" id="categoryMenu">
                            <a href="{{ route('posts.index', ['sort' => request('sort'), 'search' => request('search')]) }}" 
                               class="dropdown-item-custom {{ !request('catalog') ? 'active' : '' }}">
                                <i class="bi bi-grid-fill"></i>
                                <span>Todas las categorías</span>
                                @if(!request('catalog'))
                                    <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                                @endif
                            </a>
                            @foreach($catalogs as $catalog)
                                <a href="{{ route('posts.index', ['catalog' => $catalog->slug, 'sort' => request('sort'), 'search' => request('search')]) }}" 
                                   class="dropdown-item-custom {{ request('catalog') == $catalog->slug ? 'active' : '' }}">
                                    <i class="bi bi-folder-fill"></i>
                                    <span>{{ $catalog->nombre }}</span>
                                    @if(request('catalog') == $catalog->slug)
                                        <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts -->
        @forelse($posts as $post)
            <div class="col-md-12 mt-2">
                <div class="card paste">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <a href="{{ route('posts.show', $post->id) }}">
                                    <h6 class="card-title card-sky mb-1"><strong>{{ $post->titulo }}</strong></h6>
                                </a>
                                <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                                    @if($post->catalog)
                                        <span class="badge bg-secondary text-white small">{{ $post->catalog->nombre }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="post-views">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                                <span>{{ number_format($post->views) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12 mt-2">
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="text-muted mt-3">No se encontraron posts</h5>
                    <p class="text-muted">
                        @if(request('search'))
                            No hay resultados para "{{ request('search') }}"
                        @elseif(request('catalog'))
                            No hay posts en este catálogo
                        @else
                            Aún no hay posts publicados
                        @endif
                    </p>
                </div>
            </div>
        @endforelse

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<style>
/* Label del filtro */
.filter-label {
    display: block;
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

.filter-label i {
    color: #667eea;
}

/* Contenedor del dropdown */
.dropdown-custom {
    position: relative;
    width: 100%;
}

/* Botón trigger */
.dropdown-toggle-custom {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: left;
}

.dropdown-toggle-custom:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}

.dropdown-toggle-custom:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.dropdown-value {
    flex: 1;
    font-weight: 500;
}

.dropdown-arrow {
    margin-left: 12px;
    color: #667eea;
    transition: transform 0.3s ease;
    font-size: 1rem;
}

.dropdown-custom.open .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-custom.open .dropdown-toggle-custom {
    border-color: #667eea;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

/* Menú desplegable */
.dropdown-menu-custom {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #667eea;
    border-top: none;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
}

.dropdown-custom.open .dropdown-menu-custom {
    max-height: 400px;
    opacity: 1;
    visibility: visible;
    overflow-y: auto;
}

/* Items del menú */
.dropdown-item-custom {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #495057;
    text-decoration: none;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.dropdown-item-custom:last-child {
    border-bottom: none;
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
}

.dropdown-item-custom i:first-child {
    color: #667eea;
    font-size: 1.1rem;
}

.dropdown-item-custom span {
    flex: 1;
    font-weight: 500;
}

.dropdown-item-custom:hover {
    background-color: #f8f9ff;
    padding-left: 20px;
}

.dropdown-item-custom.active {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.12) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
    font-weight: 600;
    border-left: 4px solid #667eea;
    padding-left: 12px;
}

.dropdown-item-custom.active:hover {
    padding-left: 16px;
}

.dropdown-item-custom.active span {
    color: #667eea;
}

/* Scrollbar */
.dropdown-menu-custom::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu-custom::-webkit-scrollbar-track {
    background: #f1f3f5;
}

.dropdown-menu-custom::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

.dropdown-menu-custom::-webkit-scrollbar-thumb:hover {
    background: #5568d3;
}

/* Contador de visitas en posts */
.post-views {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    padding: 6px 12px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.post-views svg {
    color: #667eea;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.post-views span {
    color: #495057;
    font-weight: 600;
}

/* Estilos para las secciones del menú */
.dropdown-section-title {
    padding: 8px 16px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #667eea;
    letter-spacing: 0.5px;
    background: #f8f9ff;
}

.dropdown-divider {
    height: 1px;
    background: #e9ecef;
    margin: 8px 0;
}

/* Responsive */
@media (max-width: 768px) {
    .row.g-3 {
        gap: 1rem !important;
    }
    
    .dropdown-toggle-custom {
        padding: 10px 14px;
        font-size: 0.9rem;
    }
    
    .dropdown-item-custom {
        padding: 10px 14px;
        font-size: 0.9rem;
    }
    
    .dropdown-item-custom:hover {
        padding-left: 18px;
    }
    
    .dropdown-item-custom.active {
        padding-left: 10px;
    }
    
    .dropdown-item-custom.active:hover {
        padding-left: 14px;
    }
}

@media (max-width: 576px) {
    .filter-label {
        font-size: 0.9rem;
    }
    
    .dropdown-toggle-custom {
        padding: 10px 12px;
        font-size: 0.875rem;
    }
    
    .dropdown-arrow {
        font-size: 0.9rem;
    }
    
    .dropdown-item-custom {
        padding: 10px 12px;
        font-size: 0.875rem;
        gap: 10px;
    }
    
    .dropdown-menu-custom {
        max-height: 300px;
    }
    
    .post-views {
        font-size: 0.8rem;
        padding: 3px 6px;
    }
    
    .post-views svg {
        width: 16px;
        height: 16px;
    }
}
</style>

<script>
// Referencias globales para poder remover listeners correctamente
let _closeDropdowns = null;
let _handleEscape = null;

function initDropdowns() {
    // Remover listeners globales anteriores si existen
    if (_closeDropdowns) document.removeEventListener('click', _closeDropdowns);
    if (_handleEscape) document.removeEventListener('keydown', _handleEscape);

    // Clonar botones para eliminar listeners duplicados acumulados
    const sortTriggerOld = document.getElementById('sortDropdown');
    const categoryTriggerOld = document.getElementById('categoryDropdown');

    if (sortTriggerOld) {
        const clone = sortTriggerOld.cloneNode(true);
        sortTriggerOld.parentNode.replaceChild(clone, sortTriggerOld);
    }
    if (categoryTriggerOld) {
        const clone = categoryTriggerOld.cloneNode(true);
        categoryTriggerOld.parentNode.replaceChild(clone, categoryTriggerOld);
    }

    // Re-seleccionar elementos después del clone
    const sortDropdown = document.querySelector('#sortDropdown')?.closest('.dropdown-custom');
    const sortTrigger = document.getElementById('sortDropdown');
    const sortMenu = document.getElementById('sortMenu');
    const categoryDropdown = document.querySelector('#categoryDropdown')?.closest('.dropdown-custom');
    const categoryTrigger = document.getElementById('categoryDropdown');
    const categoryMenu = document.getElementById('categoryMenu');

    // Listener del dropdown "Ordenar por"
    if (sortTrigger && sortDropdown) {
        sortTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            categoryDropdown?.classList.remove('open');
            sortDropdown.classList.toggle('open');
        });
    }

    // Listener del dropdown "Categoría"
    if (categoryTrigger && categoryDropdown) {
        categoryTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            sortDropdown?.classList.remove('open');
            categoryDropdown.classList.toggle('open');
        });
    }

    // Cerrar al hacer click fuera
    _closeDropdowns = function(e) {
        if (sortDropdown && !sortDropdown.contains(e.target)) {
            sortDropdown.classList.remove('open');
        }
        if (categoryDropdown && !categoryDropdown.contains(e.target)) {
            categoryDropdown.classList.remove('open');
        }
    };

    // Cerrar con tecla Escape
    _handleEscape = function(e) {
        if (e.key === 'Escape') {
            sortDropdown?.classList.remove('open');
            categoryDropdown?.classList.remove('open');
        }
    };

    document.addEventListener('click', _closeDropdowns);
    document.addEventListener('keydown', _handleEscape);

    // Cerrar al seleccionar una opción
    sortMenu?.addEventListener('click', function(e) {
        if (e.target.closest('a')) sortDropdown?.classList.remove('open');
    });

    categoryMenu?.addEventListener('click', function(e) {
        if (e.target.closest('a')) categoryDropdown?.classList.remove('open');
    });
}

document.addEventListener('DOMContentLoaded', initDropdowns);
document.addEventListener('livewire:navigated', initDropdowns);
</script>
@endsection