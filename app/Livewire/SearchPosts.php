<?php

namespace App\Livewire;

use App\Models\Catalog;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class SearchPosts extends Component
{
    use WithPagination;

    public string $search = '';
    public string $catalog = '';
    public string $sort = '';

    // ✅ Reiniciar paginación cuando cambie cualquier filtro
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCatalog(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        // Leer parámetros de la URL al cargar por primera vez
        $this->search  = request('search', '');
        $this->catalog = request('catalog', '');
        $this->sort    = request('sort', '');
    }

    public function render()
    {
        $query = Post::query();

        // ✅ Búsqueda en tiempo real por título y nombre de catálogo
        if ($this->search !== '') {
            $query->search($this->search);
        }

        // Filtro por catálogo
        if ($this->catalog !== '') {
            $catalogModel = Catalog::where('slug', $this->catalog)->first();
            if ($catalogModel) {
                $query->where('posts.catalog_id', $catalogModel->id);
            }
        }

        // Ordenamiento
        switch ($this->sort) {
            case 'most_viewed':
                $query->orderBy('posts.views', 'desc');
                break;
            default:
                $query->orderBy('posts.created_at', 'desc');
        }

        $posts    = $query->with('catalog')->paginate(15);
        $catalogs = Catalog::active()->orderBy('nombre')->get();

        return view('livewire.search-posts', compact('posts', 'catalogs'));
    }
}