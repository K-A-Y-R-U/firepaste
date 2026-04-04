@extends('layout')

@section('title', 'Mis Canjes de Códigos')

@section('page-title', 'Mis Canjes de Códigos')

@section('content')
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-200 mb-2">Mis Canjes de Códigos</h1>
        <p class="text-gray-600 dark:text-gray-400">Historial de códigos de regalo canjeados</p>
    </div>

    <!-- User VIP Status Summary -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="text-center md:text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Estado VIP</div>
                        @if($vipStatus['is_active'])
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Activo ({{ $vipStatus['days_remaining'] }} días)
                            </div>
                        @else
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                Inactivo
                            </div>
                        @endif
                    </div>
                    
                    @if($vipStatus['is_active'])
                        <div class="text-center md:text-right">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Expira el</div>
                            <div class="font-medium text-purple-600 dark:text-purple-400">
                                {{ $vipStatus['expires_at']->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Redemptions List -->
    <div class="max-w-4xl mx-auto">
        @if($redemptions->count() > 0)
            <div class="grid gap-6">
                @foreach($redemptions as $redemption)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                <div class="mb-2 md:mb-0">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-3 py-1 rounded-full text-sm font-mono font-bold">
                                            {{ $redemption->giftCode->code }}
                                        </div>
                                        <div class="ml-3 text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $redemption->giftCode->vip_days }} días VIP
                                        </div>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Canjeado el {{ $redemption->redeemed_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    @if($redemption->isVipActive())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Expirado
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Redemption Details -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">VIP inició:</span>
                                        <div class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $redemption->vip_starts_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">VIP termina:</span>
                                        <div class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $redemption->vip_ends_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Días restantes:</span>
                                        <div class="font-medium {{ $redemption->isVipActive() ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                            {{ $redemption->getDaysRemaining() }} días
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @if($redemption->isVipActive())
                                <div class="mt-4">
                                    @php
                                        $totalDays = $redemption->vip_starts_at->diffInDays($redemption->vip_ends_at);
                                        $remainingDays = $redemption->getDaysRemaining();
                                        $usedDays = $totalDays - $remainingDays;
                                        $percentage = $totalDays > 0 ? ($usedDays / $totalDays) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <span>Progreso: {{ $usedDays }}/{{ $totalDays }} días utilizados</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary Stats -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Estadísticas de Canjes</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $redemptions->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Códigos canjeados</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $redemptions->sum(function($r) { return $r->giftCode->vip_days; }) }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total días VIP obtenidos</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $redemptions->where('vip_ends_at', '>', now())->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Códigos activos</div>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">No has canjeado códigos</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Cuando canjees tu primer código de regalo, aparecerá aquí.</p>
                
                <a href="{{ route('gift-codes.redeem') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-700 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                    Canjear mi primer código
                </a>
            </div>
        @endif
    </div>

    <!-- Navigation -->
    <div class="max-w-4xl mx-auto mt-8 text-center space-x-4">
        <a href="{{ route('gift-codes.redeem') }}" 
           class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
            </svg>
            Canjear Código
        </a>
        <span class="text-gray-400 dark:text-gray-600">|</span>
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
            </svg>
            Dashboard
        </a>
    </div>
@endsection