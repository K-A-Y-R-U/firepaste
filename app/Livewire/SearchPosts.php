<?php

namespace App\Livewire;

use App\Models\Catalog;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class SearchPosts extends Component
{
    use WithPagination;

    public string $search  = '';
    public string $catalog = '';
    public string $sort    = '';

    public function updatedSearch(): void  { $this->resetPage(); }
    public function updatedCatalog(): void { $this->resetPage(); }
    public function updatedSort(): void    { $this->resetPage(); }

    public function mount(): void
    {
        $this->search  = request('search', '');
        $this->catalog = request('catalog', '');
        $this->sort    = request('sort', '');
    }

    public function render()
    {
        $query = Post::query();

        if ($this->search !== '') {
            // scopeSearch ya hace el leftJoin y el select('posts.*')
            $query->search($this->search);
        }

        if ($this->catalog !== '') {
            $catalogModel = Catalog::where('slug', $this->catalog)->first();
            if ($catalogModel) {
                $query->where('catalog_id', $catalogModel->id);
            }
        }

        if ($this->sort === 'most_viewed') {
            $query->orderBy('views', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $posts    = $query->with('catalog')->paginate(15);
        $catalogs = Catalog::active()->orderBy('nombre')->get();

        return view('livewire.search-posts', compact('posts', 'catalogs'));
    }
}