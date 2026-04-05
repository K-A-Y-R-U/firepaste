@extends('layouts.app')

@section('title', 'Editar Perfil - ' . Auth::user()->name)

@section('content')
@php $vipStatus = Auth::user()->getVipStatus(); @endphp

<div class="container py-4" style="min-height: calc(100vh - 200px);">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-welcome">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div>
                            <h4 class="welcome-title mb-1">{{ Auth::user()->name }}</h4>
                            <p class="welcome-sub mb-0">Miembro desde {{ Auth::user()->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn-back">
                        <i class="bi bi-arrow-left me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Sidebar --}}
        <div class="col-md-4">

            {{-- Info usuario --}}
            <div class="dashboard-card mb-4">
                <div class="dashboard-card-header">
                    <i class="bi bi-person-fill me-2"></i>Mi Cuenta
                </div>
                <div class="dashboard-card-body text-center">
                    <div class="profile-avatar-xl mx-auto mb-3">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="profile-name">{{ Auth::user()->name }}</div>
                    <div class="profile-email mb-3">{{ Auth::user()->email }}</div>

                    @if(Auth::user()->email_verified_at)
                        <span class="status-badge verified"><i class="bi bi-patch-check-fill me-1"></i>Email Verificado</span>
                    @else
                        <span class="status-badge unverified"><i class="bi bi-exclamation-circle-fill me-1"></i>Email No Verificado</span>
                    @endif
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="dashboard-card mb-4">
                <div class="dashboard-card-header">
                    <i class="bi bi-bar-chart-fill me-2"></i>Estadísticas
                </div>
                <div class="dashboard-card-body">
                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-calendar me-1"></i>Días como miembro</span>
                        <span class="info-value stat-highlight">{{ Auth::user()->created_at->diffInDays(now()) }}</span>
                    </div>
                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-gift me-1"></i>Códigos canjeados</span>
                        <span class="info-value stat-highlight purple">{{ Auth::user()->giftCodeRedemptions()->count() }}</span>
                    </div>
                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-star me-1"></i>Estado VIP</span>
                        <span class="info-value {{ $vipStatus['is_active'] ? 'text-success' : '' }}">
                            {{ $vipStatus['is_active'] ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Acciones rápidas --}}
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <i class="bi bi-lightning-charge-fill me-2"></i>Acciones Rápidas
                </div>
                <div class="dashboard-card-body">
                    <a href="{{ route('gift-codes.redeem') }}" class="dashboard-action-item action-blue mb-2">
                        <i class="bi bi-gift me-2"></i><span>Canjear Código</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                    <a href="{{ route('gift-codes.my-redemptions') }}" class="dashboard-action-item action-purple">
                        <i class="bi bi-clock-history me-2"></i><span>Mis Canjes</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- Formularios --}}
        <div class="col-md-8">

            {{-- Información personal --}}
            <div class="dashboard-card mb-4">
                <div class="dashboard-card-header">
                    <i class="bi bi-person-vcard-fill me-2"></i>Información Personal
                    <span class="header-sub">Actualiza tu nombre y correo electrónico</span>
                </div>
                <div class="dashboard-card-body">
                    @if(class_exists('\Livewire\Component'))
                        @livewire('profile.update-profile-information-form')
                    @else
                        <form method="POST" action="#">
                            @csrf @method('patch')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Nombre Completo</label>
                                    <input type="text" name="name" class="form-input-custom" value="{{ old('name', Auth::user()->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Correo Electrónico</label>
                                    <input type="email" name="email" class="form-input-custom" value="{{ old('email', Auth::user()->email) }}" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="save-btn">
                                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Cambiar contraseña --}}
            <div class="dashboard-card mb-4">
                <div class="dashboard-card-header">
                    <i class="bi bi-lock-fill me-2"></i>Cambiar Contraseña
                    <span class="header-sub">Usa una contraseña larga y segura</span>
                </div>
                <div class="dashboard-card-body">
                    @if(class_exists('\Livewire\Component'))
                        @livewire('profile.update-password-form')
                    @else
                        <form method="POST" action="#">
                            @csrf @method('put')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-custom">Contraseña Actual</label>
                                    <input type="password" name="current_password" class="form-input-custom" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Nueva Contraseña</label>
                                    <input type="password" name="password" class="form-input-custom" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Confirmar Contraseña</label>
                                    <input type="password" name="password_confirmation" class="form-input-custom" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="save-btn warning">
                                    <i class="bi bi-shield-lock me-2"></i>Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Zona peligrosa --}}
            <div class="dashboard-card danger-card">
                <div class="dashboard-card-header danger-header">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Zona Peligrosa
                    <span class="header-sub">Acciones irreversibles de tu cuenta</span>
                </div>
                <div class="dashboard-card-body">
                    <p class="danger-text">Una vez que elimines tu cuenta, todos los recursos y datos serán eliminados permanentemente. Esta acción no se puede deshacer.</p>
                    @if(class_exists('\Livewire\Component'))
                        @livewire('profile.delete-user-form')
                    @else
                        <div class="info-notice">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Para eliminar tu cuenta, contacta al soporte técnico.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .dashboard-welcome {
        background: #fff; border-radius: 14px; padding: 1.5rem 2rem;
        border: 1px solid #e9ecef; box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .welcome-title { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; }
    .welcome-sub { color: #888; font-size: 0.9rem; }

    .btn-back {
        display: inline-flex; align-items: center; padding: 0.5rem 1.2rem;
        border-radius: 8px; background: #f1f3f5; color: #495057;
        font-size: 0.88rem; font-weight: 600; text-decoration: none; transition: all 0.2s;
    }
    .btn-back:hover { background: #e9ecef; color: #333; }

    .profile-avatar-lg {
        width: 56px; height: 56px; border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 700; flex-shrink: 0;
    }
    .profile-avatar-xl {
        width: 72px; height: 72px; border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 700;
    }
    .profile-name { font-weight: 700; color: #1a1a2e; font-size: 1rem; margin-bottom: 0.2rem; }
    .profile-email { color: #888; font-size: 0.85rem; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
    }
    .status-badge.verified { background: #f0fdf4; color: #16a34a; }
    .status-badge.unverified { background: #fffbeb; color: #d97706; }

    .dashboard-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e9ecef; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden;
    }
    .dashboard-card-header {
        padding: 1rem 1.5rem; font-weight: 700; font-size: 0.88rem; color: #495057;
        border-bottom: 1px solid #f1f3f5; background: #fafafa;
        text-transform: uppercase; letter-spacing: 0.5px;
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
    }
    .dashboard-card-header i { color: #667eea; }
    .header-sub { font-size: 0.78rem; font-weight: 400; color: #aaa; text-transform: none; letter-spacing: 0; margin-left: auto; }
    .dashboard-card-body { padding: 1.5rem; }

    .account-info-item { display: flex; flex-direction: column; gap: 0.2rem; padding: 0.6rem 0; border-bottom: 1px solid #f1f3f5; }
    .account-info-item:last-child { border-bottom: none; }
    .info-label { font-size: 0.75rem; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
    .info-value { font-size: 0.92rem; color: #333; font-weight: 600; }
    .stat-highlight { color: #667eea; font-size: 1.1rem; font-weight: 800; }
    .stat-highlight.purple { color: #764ba2; }

    .dashboard-action-item {
        display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem;
        border-radius: 10px; text-decoration: none; font-size: 0.88rem; font-weight: 500;
        color: #495057; transition: all 0.2s;
    }
    .dashboard-action-item:hover { transform: translateX(3px); color: #333; }
    .action-blue { background: #eff6ff; }
    .action-blue:hover { background: #dbeafe; }
    .action-purple { background: #f5f3ff; }
    .action-purple:hover { background: #ede9fe; }

    .form-label-custom {
        display: block; font-size: 0.82rem; font-weight: 600; color: #444;
        margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .form-input-custom {
        width: 100%; padding: 0.8rem 1rem; border: 2px solid #e8e8ee;
        border-radius: 10px; font-size: 0.95rem; color: #1a1a2e; background: #fafafa;
        transition: all 0.2s; outline: none;
    }
    .form-input-custom:focus {
        border-color: #667eea; background: #fff;
        box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
    }

    .save-btn {
        display: inline-flex; align-items: center; padding: 0.7rem 1.5rem;
        background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;
        border: none; border-radius: 10px; font-weight: 700; font-size: 0.9rem;
        cursor: pointer; transition: all 0.25s;
    }
    .save-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(102,126,234,0.4); }
    .save-btn.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .save-btn.warning:hover { box-shadow: 0 6px 20px rgba(245,158,11,0.4); }

    .danger-card { border-color: #fecaca; }
    .danger-header { background: #fff5f5; color: #dc2626; border-bottom-color: #fecaca; }
    .danger-header i { color: #dc2626; }
    .danger-text { color: #666; font-size: 0.9rem; margin-bottom: 1rem; }

    .info-notice {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
        padding: 0.9rem 1.2rem; font-size: 0.88rem; color: #1d4ed8;
        display: flex; align-items: center;
    }
</style>
@endsection