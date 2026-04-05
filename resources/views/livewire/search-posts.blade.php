<div class="col-md-10">

    {{-- Buscador --}}
    <div class="mt-1">
        <div class="input-group mb-3">
            <input
                type="text"
                wire:model.live.debounce.400ms="search"
                class="form-control search-input"
                placeholder="Buscar..."
                aria-label="Buscar..."
                aria-describedby="button-buscar"
            >
            <span class="input-group-text bg-white border-start-0 border-end-0 px-1" wire:loading>
                <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
            </span>
            <button type="button" class="btn btn-main search-btn" id="button-buscar">Buscar</button>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="mb-4">
        <div class="row g-3">

            {{-- Ordenar por --}}
            <div class="col-md-6">
                <label class="filter-label mb-2">
                    <i class="bi bi-sort-down me-2"></i>Ordenar por
                </label>
                <div class="dropdown-custom">
                    <button type="button" class="dropdown-toggle-custom" id="sortDropdown">
                        <span class="dropdown-value">
                            @if($sort === 'most_viewed') Lo más visto @else Más reciente @endif
                        </span>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="sortMenu">
                        <div class="dropdown-item-custom {{ $sort !== 'most_viewed' ? 'active' : '' }}"
                             wire:click="$set('sort', '')">
                            <i class="bi bi-clock-fill"></i>
                            <span>Más reciente</span>
                            @if($sort !== 'most_viewed')
                                <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                            @endif
                        </div>
                        <div class="dropdown-item-custom {{ $sort === 'most_viewed' ? 'active' : '' }}"
                             wire:click="$set('sort', 'most_viewed')">
                            <i class="bi bi-eye-fill"></i>
                            <span>Lo más visto</span>
                            @if($sort === 'most_viewed')
                                <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filtrar por categoría --}}
            <div class="col-md-6">
                <label class="filter-label mb-2">
                    <i class="bi bi-funnel-fill me-2"></i>Filtrar por categoría
                </label>
                <div class="dropdown-custom">
                    <button type="button" class="dropdown-toggle-custom" id="categoryDropdown">
                        <span class="dropdown-value">
                            @if($catalog)
                                {{ $catalogs->where('slug', $catalog)->first()->nombre ?? 'Todas las categorías' }}
                            @else
                                Todas las categorías
                            @endif
                        </span>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="categoryMenu">
                        <div class="dropdown-item-custom {{ !$catalog ? 'active' : '' }}"
                             wire:click="$set('catalog', '')">
                            <i class="bi bi-grid-fill"></i>
                            <span>Todas las categorías</span>
                            @if(!$catalog)
                                <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                            @endif
                        </div>
                        @foreach($catalogs as $cat)
                            <div class="dropdown-item-custom {{ $catalog === $cat->slug ? 'active' : '' }}"
                                 wire:click="$set('catalog', '{{ $cat->slug }}')">
                                <i class="bi bi-folder-fill"></i>
                                <span>{{ $cat->nombre }}</span>
                                @if($catalog === $cat->slug)
                                    <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Posts --}}
    <div wire:loading.class="opacity-50">
        @forelse($posts as $post)
            <div class="col-md-12 mt-2">
                <div class="card paste">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div class="flex-grow-1">
                                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">
                                    <h6 class="card-title card-sky mb-0">
                                        <strong>{{ $post->titulo }}</strong>
                                    </h6>
                                </a>
                                @if($post->catalog)
                                    <span class="post-catalog-badge mt-1">
                                        <i class="bi bi-folder2 me-1"></i>{{ $post->catalog->nombre }}
                                    </span>
                                @endif
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
                        @if($search)
                            No hay resultados para "{{ $search }}"
                        @elseif($catalog)
                            No hay posts en este catálogo
                        @else
                            Aún no hay posts publicados
                        @endif
                    </p>
                </div>
            </div>
        @endforelse

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
    </div>

</div>