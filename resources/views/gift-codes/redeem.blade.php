@extends('layouts.app')

@section('title', __('Canjear Código de Regalo'))

@section('content')
<div class="container py-4" style="min-height: calc(100vh - 200px);">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-welcome">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="welcome-title mb-1"><i class="bi bi-gift-fill me-2" style="color:#667eea;"></i>{{ __('Canjear Código de Regalo') }}</h4>
                        <p class="welcome-sub mb-0">{{ __('Ingresa tu código para obtener días VIP') }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn-back">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Volver al Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-4">

        {{-- Info del usuario --}}
        <div class="col-md-4">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <i class="bi bi-person-fill me-2"></i>{{ __('Tu Cuenta') }}
                </div>
                <div class="dashboard-card-body">
                    <div class="user-avatar-wrap">
                        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="account-info-item">
                        <span class="info-label"><i class="bi bi-patch-check me-1"></i>{{ __('Estado VIP') }}</span>
                        @if($vipStatus['is_active'])
                            <span class="status-badge active"><i class="bi bi-check-circle-fill me-1"></i>{{ __('Activo') }} — {{ $vipStatus['days_remaining'] }} {{ __('días') }}</span>
                        @else
                            <span class="status-badge inactive"><i class="bi bi-x-circle-fill me-1"></i>{{ __('Inactivo') }}</span>
                        @endif
                    </div>

                    @if($vipStatus['is_active'] && $vipStatus['expires_at'])
                        <div class="account-info-item">
                            <span class="info-label"><i class="bi bi-calendar me-1"></i>{{ __('Expira el') }}</span>
                            <span class="info-value">{{ $vipStatus['expires_at']->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif

                    <div class="divider"></div>

                    <a href="{{ route('gift-codes.my-redemptions') }}" class="dashboard-link-item mt-2">
                        <i class="bi bi-clock-history me-2"></i>{{ __('Ver mis canjes') }}
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="col-md-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <i class="bi bi-key-fill me-2"></i>{{ __('Ingresar Código') }}
                </div>
                <div class="dashboard-card-body">

                    @if(session('success'))
                        <div class="alert-custom success mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-custom error mb-4">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('gift-codes.redeem.process') }}">
                        @csrf
                        <div class="code-input-wrap">
                            <label class="auth-label">{{ __('Código de Regalo') }}</label>
                            <input
                                type="text"
                                name="code"
                                id="code"
                                placeholder="XXXXXXXXXX"
                                value="{{ old('code') }}"
                                class="code-input @error('code') is-invalid @enderror"
                                maxlength="20"
                                required
                            >
                            @error('code')
                                <span class="invalid-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="redeem-btn">
                            <i class="bi bi-gift me-2"></i>{{ __('Canjear Código') }}
                        </button>
                    </form>

                    <div class="help-box mt-4">
                        <div class="help-title"><i class="bi bi-info-circle me-2"></i>{{ __('¿Cómo funciona?') }}</div>
                        <ul class="help-list">
                            <li>{{ __('Los códigos son de un solo uso por usuario') }}</li>
                            <li>{{ __('El tiempo VIP se suma a tu membresía actual') }}</li>
                            <li>{{ __('Los códigos pueden tener fecha de expiración') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .dashboard-welcome {
        background: #fff;
        border-radius: 14px;
        padding: 1.5rem 2rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .welcome-title { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; }
    .welcome-sub { color: #888; font-size: 0.9rem; }

    .btn-back {
        display: inline-flex; align-items: center;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        background: #f1f3f5;
        color: #495057;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-back:hover { background: #e9ecef; color: #333; }

    .dashboard-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .dashboard-card-header {
        padding: 1rem 1.5rem;
        font-weight: 700;
        font-size: 0.88rem;
        color: #495057;
        border-bottom: 1px solid #f1f3f5;
        background: #fafafa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .dashboard-card-header i { color: #667eea; }
    .dashboard-card-body { padding: 1.5rem; }

    .user-avatar-wrap { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .user-avatar {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; font-weight: 700;
        flex-shrink: 0;
    }
    .user-name { font-weight: 700; color: #1a1a2e; font-size: 0.95rem; }
    .user-email { color: #888; font-size: 0.82rem; }

    .divider { height: 1px; background: #f1f3f5; margin: 1rem 0; }

    .account-info-item { display: flex; flex-direction: column; gap: 0.3rem; margin-bottom: 0.75rem; }
    .info-label { font-size: 0.78rem; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
    .info-value { font-size: 0.9rem; color: #333; font-weight: 500; }

    .status-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
    .status-badge.active { background: #f0fdf4; color: #16a34a; }
    .status-badge.inactive { background: #f8f9fa; color: #888; }

    .dashboard-link-item {
        display: flex; align-items: center;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.88rem; font-weight: 500;
        color: #667eea;
        background: #f8f9ff;
        transition: all 0.2s;
    }
    .dashboard-link-item:hover { background: #eff1ff; transform: translateX(3px); }

    .auth-label {
        display: block; font-size: 0.82rem; font-weight: 600; color: #444;
        margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .code-input-wrap { margin-bottom: 1.25rem; }
    .code-input {
        width: 100%; padding: 1rem;
        border: 2px solid #e8e8ee; border-radius: 10px;
        font-size: 1.4rem; font-weight: 700; letter-spacing: 6px;
        text-align: center; text-transform: uppercase;
        color: #1a1a2e; background: #fafafa;
        transition: all 0.2s; outline: none;
        font-family: 'Courier New', monospace;
    }
    .code-input:focus { border-color: #667eea; background: #fff; box-shadow: 0 0 0 4px rgba(102,126,234,0.1); }
    .code-input.is-invalid { border-color: #ef4444; }
    .invalid-msg { font-size: 0.8rem; color: #ef4444; margin-top: 0.4rem; display: block; }

    .redeem-btn {
        width: 100%; padding: 0.9rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; border: none; border-radius: 10px;
        font-size: 1rem; font-weight: 700; cursor: pointer;
        transition: all 0.25s;
    }
    .redeem-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(102,126,234,0.4); }

    .help-box {
        background: #f8f9ff; border-radius: 10px;
        padding: 1rem 1.25rem; border: 1px solid #e8ecff;
    }
    .help-title { font-size: 0.88rem; font-weight: 700; color: #667eea; margin-bottom: 0.6rem; }
    .help-list { margin: 0; padding-left: 1rem; }
    .help-list li { font-size: 0.82rem; color: #666; margin-bottom: 0.3rem; }

    .alert-custom {
        padding: 0.8rem 1rem; border-radius: 10px;
        font-size: 0.88rem; font-weight: 500;
        display: flex; align-items: center;
    }
    .alert-custom.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .alert-custom.error { background: #fff5f5; color: #dc2626; border: 1px solid #fecaca; }
</style>

<script>
    document.getElementById('code').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
</script>
@endsection