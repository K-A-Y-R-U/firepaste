@extends('layouts.app')

@section('title', 'Mis Canjes de Códigos')

@section('content')
<div class="container py-4" style="min-height: calc(100vh - 200px);">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-welcome">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="welcome-title mb-1"><i class="bi bi-clock-history me-2" style="color:#667eea;"></i>Mis Canjes de Códigos</h4>
                        <p class="welcome-sub mb-0">Historial de códigos de regalo canjeados</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('gift-codes.redeem') }}" class="btn-action">
                            <i class="bi bi-gift me-1"></i> Canjear Código
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn-back">
                            <i class="bi bi-arrow-left me-1"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resumen VIP --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card vip-card {{ $vipStatus['is_active'] ? 'vip-active' : 'vip-inactive' }}">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="vip-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <div class="vip-name">{{ $user->name }}</div>
                            <div class="vip-email">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div class="text-center">
                            <div class="vip-stat-label">Estado VIP</div>
                            @if($vipStatus['is_active'])
                                <span class="vip-stat-value active">✨ Activo ({{ $vipStatus['days_remaining'] }} días)</span>
                            @else
                                <span class="vip-stat-value inactive">Inactivo</span>
                            @endif
                        </div>
                        @if($vipStatus['is_active'])
                            <div class="text-center">
                                <div class="vip-stat-label">Expira el</div>
                                <div class="vip-stat-value">{{ $vipStatus['expires_at']->format('d/m/Y') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    @if($redemptions->count() > 0)
    <div class="row mb-4 g-3">
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-number" style="color:#667eea;">{{ $redemptions->count() }}</div>
                <div class="stat-label">Códigos canjeados</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-number" style="color:#764ba2;">{{ $redemptions->sum(function($r) { return $r->giftCode->vip_days; }) }}</div>
                <div class="stat-label">Total días VIP</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-number" style="color:#16a34a;">{{ $redemptions->where('vip_ends_at', '>', now())->count() }}</div>
                <div class="stat-label">Códigos activos</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Lista de canjes --}}
    @if($redemptions->count() > 0)
        <div class="row g-3">
            @foreach($redemptions as $redemption)
            @php
                $totalDays = $redemption->vip_starts_at->diffInDays($redemption->vip_ends_at);
                $remainingDays = $redemption->getDaysRemaining();
                $usedDays = $totalDays - $remainingDays;
                $percentage = $totalDays > 0 ? ($usedDays / $totalDays) * 100 : 0;
            @endphp
            <div class="col-12">
                <div class="dashboard-card redemption-card">
                    <div class="redemption-header">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="code-badge">{{ $redemption->giftCode->code }}</span>
                            <span class="days-badge"><i class="bi bi-star-fill me-1"></i>{{ $redemption->giftCode->vip_days }} días VIP</span>
                            <span class="redeem-date"><i class="bi bi-calendar me-1"></i>Canjeado el {{ $redemption->redeemed_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($redemption->isVipActive())
                            <span class="status-badge active"><i class="bi bi-check-circle-fill me-1"></i>Activo</span>
                        @else
                            <span class="status-badge expired"><i class="bi bi-x-circle-fill me-1"></i>Expirado</span>
                        @endif
                    </div>

                    <div class="redemption-details">
                        <div class="detail-item">
                            <span class="detail-label">VIP inició</span>
                            <span class="detail-value">{{ $redemption->vip_starts_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">VIP termina</span>
                            <span class="detail-value">{{ $redemption->vip_ends_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Días restantes</span>
                            <span class="detail-value {{ $redemption->isVipActive() ? 'text-success' : 'text-muted' }}">{{ $remainingDays }} días</span>
                        </div>
                    </div>

                    @if($redemption->isVipActive())
                        <div class="progress-wrap">
                            <div class="progress-label">
                                <span>Progreso: {{ $usedDays }}/{{ $totalDays }} días utilizados</span>
                                <span>{{ round($percentage) }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    @else
        {{-- Empty state --}}
        <div class="row">
            <div class="col-12">
                <div class="dashboard-card empty-state">
                    <i class="bi bi-gift empty-icon"></i>
                    <h5 class="empty-title">No has canjeado códigos</h5>
                    <p class="empty-sub">Cuando canjees tu primer código de regalo, aparecerá aquí.</p>
                    <a href="{{ route('gift-codes.redeem') }}" class="redeem-btn">
                        <i class="bi bi-gift me-2"></i>Canjear mi primer código
                    </a>
                </div>
            </div>
        </div>
    @endif

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

    .btn-action {
        display: inline-flex; align-items: center; padding: 0.5rem 1.2rem;
        border-radius: 8px; background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; font-size: 0.88rem; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-action:hover { opacity: 0.9; color: #fff; transform: translateY(-1px); }

    .dashboard-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e9ecef; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden;
    }

    /* VIP card */
    .vip-card { padding: 1.5rem 2rem; }
    .vip-active { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
    .vip-inactive { background: linear-gradient(135deg, #2d2d3a, #1a1a2e); border: none; }
    .vip-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        background: rgba(255,255,255,0.2); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; font-weight: 700; flex-shrink: 0;
    }
    .vip-name { font-weight: 700; color: #fff; font-size: 0.95rem; }
    .vip-email { color: rgba(255,255,255,0.65); font-size: 0.82rem; }
    .vip-stat-label { font-size: 0.75rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.4px; }
    .vip-stat-value { font-size: 0.88rem; font-weight: 700; color: #fff; display: block; }
    .vip-stat-value.active { color: #86efac; }
    .vip-stat-value.inactive { color: rgba(255,255,255,0.5); }

    /* Stats */
    .stat-card {
        background: #fff; border-radius: 14px; padding: 1.25rem;
        border: 1px solid #e9ecef; text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .stat-number { font-size: 2rem; font-weight: 800; line-height: 1; margin-bottom: 0.3rem; }
    .stat-label { font-size: 0.8rem; color: #888; font-weight: 500; }

    /* Redemption card */
    .redemption-card { padding: 0; }
    .redemption-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f3f5;
    }
    .code-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; padding: 4px 12px; border-radius: 6px;
        font-family: 'Courier New', monospace; font-weight: 700; font-size: 0.88rem;
    }
    .days-badge {
        background: #f8f9ff; color: #667eea;
        padding: 4px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
    }
    .redeem-date { color: #888; font-size: 0.82rem; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
    }
    .status-badge.active { background: #f0fdf4; color: #16a34a; }
    .status-badge.expired { background: #f8f9fa; color: #888; }

    .redemption-details {
        display: flex; gap: 2rem; flex-wrap: wrap;
        padding: 1rem 1.5rem; background: #fafafa;
    }
    .detail-item { display: flex; flex-direction: column; gap: 0.2rem; }
    .detail-label { font-size: 0.75rem; color: #999; text-transform: uppercase; letter-spacing: 0.4px; font-weight: 600; }
    .detail-value { font-size: 0.9rem; color: #333; font-weight: 600; }

    .progress-wrap { padding: 1rem 1.5rem; }
    .progress-label {
        display: flex; justify-content: space-between;
        font-size: 0.78rem; color: #888; margin-bottom: 0.5rem;
    }
    .progress-bar-custom { height: 6px; background: #e9ecef; border-radius: 50px; overflow: hidden; }
    .progress-fill {
        height: 100%; border-radius: 50px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transition: width 0.3s ease;
    }

    /* Empty state */
    .empty-state { padding: 4rem 2rem; text-align: center; }
    .empty-icon { font-size: 3.5rem; color: #d1d5db; display: block; margin-bottom: 1rem; }
    .empty-title { font-size: 1.2rem; font-weight: 700; color: #1a1a2e; margin-bottom: 0.5rem; }
    .empty-sub { color: #888; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .redeem-btn {
        display: inline-flex; align-items: center;
        padding: 0.8rem 1.8rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; border: none; border-radius: 10px;
        font-size: 0.95rem; font-weight: 700;
        text-decoration: none; transition: all 0.25s;
    }
    .redeem-btn:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(102,126,234,0.4); }
</style>
@endsection