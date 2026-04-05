@extends('layouts.app')

@section('title', 'Dashboard - ' . Auth::user()->name)

@section('content')
@php
    $vipStatus = Auth::user()->getVipStatus();
@endphp

<div class="container py-4" style="min-height: calc(100vh - 200px);">

    {{-- Bienvenida --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-welcome">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="welcome-title mb-1">¡Bienvenido, {{ Auth::user()->name }}!</h4>
                        <p class="welcome-sub mb-0">Aquí tienes un resumen de tu cuenta</p>
                    </div>
                    <div class="vip-badge-wrap">
                        @if($vipStatus['is_active'])
                            <span class="vip-badge active">✨ VIP ACTIVO</span>
                        @else
                            <span class="vip-badge inactive">Sin membresía</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estado VIP --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card vip-card {{ $vipStatus['is_active'] ? 'vip-active' : 'vip-inactive' }}">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h5 class="vip-card-title mb-1">
                            <i class="bi {{ $vipStatus['is_active'] ? 'bi-patch-check-fill' : 'bi-lock-fill' }} me-2"></i>
                            Estado VIP: {{ $vipStatus['is_active'] ? 'ACTIVO' : 'INACTIVO' }}
                        </h5>
                        @if($vipStatus['is_active'])
                            <p class="vip-card-sub mb-0">{{ $vipStatus['days_remaining'] }} días restantes — Expira: {{ $vipStatus['expires_at']->format('d/m/Y') }}</p>
                        @else
                            <p class="vip-card-sub mb-0">Activa tu membresía para acceder a contenido exclusivo</p>
                        @endif
                    </div>
                    @if(!$vipStatus['is_active'])
                        <a href="{{ route('gift-codes.redeem') }}" class="btn-dashboard-vip">
                            <i class="bi bi-gift me-1"></i> Activar VIP
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Cards de info --}}
    <div class="row g-4">

        {{-- Acciones rápidas --}}
        <div class="col-md-4">
            <div class="dashboard-card h-100">
                <div class="dashboard-card-header">
                    <i class="bi bi-lightning-charge-fill me-2"></i>Acciones Rápidas
                </div>
                <div class="dashboard-card-body">
                    <a href="{{ route('gift-codes.redeem') }}" class="dashboard-action-item action-blue">
                        <span class="action-icon">🎁</span>
                        <span>Canjear Código</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                    <a href="{{ route('gift-codes.my-redemptions') }}" class="dashboard-action-item action-purple">
                        <span class="action-icon">📋</span>
                        <span>Mis Canjes</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                    <a href="{{ route('posts.index') }}" class="dashboard-action-item action-green">
                        <span class="action-icon">📄</span>
                        <span>Ver Posts</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Mi cuenta --}}
        <div class="col-md-4">
            <div class="dashboard-card h-100">
                <div class="dashboard-card-header">
                    <i class="bi bi-person-fill me-2"></i>Mi Cuenta
                </div>
                <div class="dashboard-card-body">
                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-envelope me-2"></i>Email</span>
                        <span class="info-value">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-calendar me-2"></i>Miembro desde</span>
                        <span class="info-value">{{ Auth::user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-gift me-2"></i>Códigos canjeados</span>
                        <span class="info-value">{{ Auth::user()->giftCodeRedemptions()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enlaces rápidos --}}
        <div class="col-md-4">
            <div class="dashboard-card h-100">
                <div class="dashboard-card-header">
                    <i class="bi bi-link-45deg me-2"></i>Enlaces Rápidos
                </div>
                <div class="dashboard-card-body">
                    <a href="{{ route('profile') }}" class="dashboard-link-item">
                        <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </a>
                    <a href="{{ route('posts.index') }}" class="dashboard-link-item">
                        <i class="bi bi-collection me-2"></i>Explorar Contenido
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </a>
                    @if($vipStatus['is_active'])
                        <a href="#" class="dashboard-link-item link-vip">
                            <i class="bi bi-star-fill me-2"></i>Contenido VIP
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Welcome */
    .dashboard-welcome {
        background: #fff;
        border-radius: 14px;
        padding: 1.5rem 2rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    .welcome-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
    }

    .welcome-sub {
        color: #888;
        font-size: 0.9rem;
    }

    .vip-badge {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .vip-badge.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
    }

    .vip-badge.inactive {
        background: #f1f3f5;
        color: #888;
    }

    /* VIP Card */
    .dashboard-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .vip-card {
        padding: 1.5rem 2rem;
    }

    .vip-card.vip-active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .vip-card.vip-inactive {
        background: linear-gradient(135deg, #2d2d3a 0%, #1a1a2e 100%);
        border: none;
    }

    .vip-card-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }

    .vip-card-sub {
        color: rgba(255,255,255,0.7);
        font-size: 0.88rem;
    }

    .btn-dashboard-vip {
        background: #fff;
        color: #667eea;
        padding: 0.6rem 1.4rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-dashboard-vip:hover {
        background: #f0f0ff;
        color: #5a6fd6;
    }

    /* Card header */
    .dashboard-card-header {
        padding: 1rem 1.5rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: #495057;
        border-bottom: 1px solid #f1f3f5;
        background: #fafafa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dashboard-card-header i {
        color: #667eea;
    }

    .dashboard-card-body {
        padding: 1rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    /* Action items */
    .dashboard-action-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.8rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        color: #495057;
        transition: all 0.2s ease;
    }

    .dashboard-action-item:hover {
        transform: translateX(4px);
        color: #333;
    }

    .action-blue { background: #eff6ff; }
    .action-blue:hover { background: #dbeafe; }

    .action-purple { background: #f5f3ff; }
    .action-purple:hover { background: #ede9fe; }

    .action-green { background: #f0fdf4; }
    .action-green:hover { background: #dcfce7; }

    .action-icon { font-size: 1.1rem; }

    /* Account info */
    .account-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f1f3f5;
    }

    .account-info-item:last-child { border-bottom: none; }

    .info-label {
        font-size: 0.78rem;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .info-label i { color: #667eea; }

    .info-value {
        font-size: 0.92rem;
        color: #333;
        font-weight: 500;
        word-break: break-all;
    }

    /* Link items */
    .dashboard-link-item {
        display: flex;
        align-items: center;
        padding: 0.8rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        color: #667eea;
        background: #f8f9ff;
        transition: all 0.2s ease;
    }

    .dashboard-link-item:hover {
        background: #eff1ff;
        transform: translateX(4px);
        color: #5a6fd6;
    }

    .dashboard-link-item.link-vip {
        color: #764ba2;
        background: #faf5ff;
    }

    .dashboard-link-item.link-vip:hover {
        background: #f3e8ff;
        color: #6b3fa0;
    }
</style>
@endsection