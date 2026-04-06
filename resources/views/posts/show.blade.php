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
                               class="nav-link fw-bold border px-3 py-2
                               {{ request()->is('posts/*') ? 'active bg-light text-dark border-bottom border-primary' : '' }}" 
                               aria-selected="{{ request()->is('posts/*') ? 'true' : 'false' }}">
                               {{ $post->pestana }}
                            </a>
                        </li>
                        @if($post->is_vip)
                        <li class="nav-item" role="presentation">
                            <a href="{{ url('/vip/' . $post->id) }}" 
                               class="nav-link fw-bold border px-3 py-2
                               {{ request()->is('vip/*') ? 'active bg-light text-dark border-bottom border-primary' : '' }}" 
                               aria-selected="{{ request()->is('vip/*') ? 'true' : 'false' }}">
                               👑 VIP
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    <div class="tab-content border border-tertiary rounded-bottom-2 border-top-0 p-0">
                        <div class="tab-pane show active p-2 p-md-3" id="tab_content1" role="tabpanel" aria-labelledby="tab_content1" tabindex="0">
                            <div class="content-wrapper">
                                {!! $post->contenido !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_content2" role="tabpanel" aria-labelledby="tab_content2" tabindex="0"></div>
                        <div class="tab-pane fade" id="tab_content3" role="tabpanel" aria-labelledby="tab_content3" tabindex="0"></div>
                    </div>
                    
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
@endsection
