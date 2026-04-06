@extends('layouts.app')

@section('content')
<div class="container mb-5" style="min-height: calc(100vh - 200px);">
    <div class="row my-4 my-lg-5 justify-content-center">
        <livewire:search-posts />
    </div>
</div>

<style>
.filter-label {
    display: block;
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

.filter-label i {
    color: #667eea;
}

.dropdown-custom {
    position: relative;
    width: 100%;
}

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

.dropdown-item-custom {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #495057;
    text-decoration: none;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
    cursor: pointer;
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

.dropdown-item-custom.active:hover { padding-left: 16px; }
.dropdown-item-custom.active span { color: #667eea; }

.dropdown-menu-custom::-webkit-scrollbar { width: 6px; }
.dropdown-menu-custom::-webkit-scrollbar-track { background: #f1f3f5; }
.dropdown-menu-custom::-webkit-scrollbar-thumb { background: #667eea; border-radius: 3px; }
.dropdown-menu-custom::-webkit-scrollbar-thumb:hover { background: #5568d3; }

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

/* Card clickeable */
.card-hoverable {
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    cursor: pointer;
}

.card-hoverable:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
    border-color: #b3bcf5;
}

.opacity-50 {
    opacity: 0.5;
    transition: opacity 0.2s ease;
}

/* Badge de categoría inline */
.post-catalog-badge {
    display: inline-flex;
    align-items: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: #667eea;
    background: #eef0fd;
    border: 1.5px solid #b3bcf5;
    border-radius: 6px;
    padding: 3px 10px;
    margin-left: 10px;
    white-space: nowrap;
    transition: background 0.2s ease;
}

.post-catalog-badge:hover {
    background: #dde1fb;
}

.post-catalog-badge i {
    color: #667eea;
    font-size: 0.7rem;
}

/* Paginación responsive */
.pagination {
    flex-wrap: wrap;
    gap: 4px;
    justify-content: center;
}

.pagination .page-item .page-link {
    border-radius: 8px !important;
    font-size: 0.9rem;
    padding: 6px 12px;
    min-width: 38px;
    text-align: center;
}

@media (max-width: 576px) {
    .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(.disabled) {
        display: none;
    }

    /* Mostrar solo: anterior, actual ±1, siguiente */
    .pagination .page-item.active,
    .pagination .page-item.active + .page-item,
    .pagination .page-item:has(+ .page-item.active),
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: flex !important;
    }

    .pagination .page-item .page-link {
        font-size: 0.85rem;
        padding: 6px 10px;
        min-width: 36px;
    }
}

/* Paginación custom */
.custom-pagination {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

.page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1.5px solid #dee2e6;
    background: #fff;
    color: #495057;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.page-btn:hover:not(.disabled):not(.active):not(.dots) {
    border-color: #667eea;
    color: #667eea;
    background: #eef0fd;
    transform: translateY(-1px);
}

.page-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(102,126,234,0.4);
}

.page-btn.disabled {
    opacity: 0.35;
    cursor: not-allowed;
    background: #f8f9fa;
}

.page-btn.dots {
    border: none;
    background: transparent;
    cursor: default;
    color: #aaa;
    font-size: 1rem;
}

@media (max-width: 576px) {
    .page-btn {
        width: 38px;
        height: 38px;
        font-size: 0.85rem;
        border-radius: 9px;
    }
}

@media (max-width: 768px) {
    .row.g-3 { gap: 1rem !important; }
    .dropdown-toggle-custom { padding: 10px 14px; font-size: 0.9rem; }
    .dropdown-item-custom { padding: 10px 14px; font-size: 0.9rem; }
    .dropdown-item-custom:hover { padding-left: 18px; }
    .dropdown-item-custom.active { padding-left: 10px; }
    .dropdown-item-custom.active:hover { padding-left: 14px; }
}

@media (max-width: 576px) {
    .filter-label { font-size: 0.9rem; }
    .dropdown-toggle-custom { padding: 10px 12px; font-size: 0.875rem; }
    .dropdown-arrow { font-size: 0.9rem; }
    .dropdown-item-custom { padding: 10px 12px; font-size: 0.875rem; gap: 10px; }
    .dropdown-menu-custom { max-height: 300px; }
    .post-views { font-size: 0.8rem; padding: 3px 6px; }
    .post-views svg { width: 16px; height: 16px; }
}
</style>

@section('scripts')
<script>
let _closeDropdowns = null;
let _handleEscape = null;

function initDropdowns() {
    if (_closeDropdowns) document.removeEventListener('click', _closeDropdowns);
    if (_handleEscape) document.removeEventListener('keydown', _handleEscape);

    const sortTriggerOld     = document.getElementById('sortDropdown');
    const categoryTriggerOld = document.getElementById('categoryDropdown');

    if (sortTriggerOld) {
        const clone = sortTriggerOld.cloneNode(true);
        sortTriggerOld.parentNode.replaceChild(clone, sortTriggerOld);
    }
    if (categoryTriggerOld) {
        const clone = categoryTriggerOld.cloneNode(true);
        categoryTriggerOld.parentNode.replaceChild(clone, categoryTriggerOld);
    }

    const sortDropdown     = document.querySelector('#sortDropdown')?.closest('.dropdown-custom');
    const sortTrigger      = document.getElementById('sortDropdown');
    const sortMenu         = document.getElementById('sortMenu');
    const categoryDropdown = document.querySelector('#categoryDropdown')?.closest('.dropdown-custom');
    const categoryTrigger  = document.getElementById('categoryDropdown');
    const categoryMenu     = document.getElementById('categoryMenu');

    if (sortTrigger && sortDropdown) {
        sortTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            categoryDropdown?.classList.remove('open');
            sortDropdown.classList.toggle('open');
        });
    }

    if (categoryTrigger && categoryDropdown) {
        categoryTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            sortDropdown?.classList.remove('open');
            categoryDropdown.classList.toggle('open');
        });
    }

    _closeDropdowns = function(e) {
        if (sortDropdown && !sortDropdown.contains(e.target)) sortDropdown.classList.remove('open');
        if (categoryDropdown && !categoryDropdown.contains(e.target)) categoryDropdown.classList.remove('open');
    };

    _handleEscape = function(e) {
        if (e.key === 'Escape') {
            sortDropdown?.classList.remove('open');
            categoryDropdown?.classList.remove('open');
        }
    };

    document.addEventListener('click', _closeDropdowns);
    document.addEventListener('keydown', _handleEscape);

    sortMenu?.addEventListener('click', function(e) {
        if (e.target.closest('.dropdown-item-custom')) sortDropdown?.classList.remove('open');
    });

    categoryMenu?.addEventListener('click', function(e) {
        if (e.target.closest('.dropdown-item-custom')) categoryDropdown?.classList.remove('open');
    });
}

document.addEventListener('DOMContentLoaded', initDropdowns);
document.addEventListener('livewire:navigated', initDropdowns);
document.addEventListener('livewire:updated', initDropdowns);
</script>
@endsection
@endsection