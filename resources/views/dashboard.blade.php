@extends('layout')

@section('title', 'Dashboard - ' . Auth::user()->name)

@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Card -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        ¡Bienvenido, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Aquí tienes un resumen de tu cuenta
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- VIP Status Card -->
    @php
        $vipStatus = Auth::user()->getVipStatus();
    @endphp
    
    <div class="bg-gradient-to-r {{ $vipStatus['is_active'] ? 'from-purple-500 to-blue-600' : 'from-gray-400 to-gray-600' }} dark:from-gray-700 dark:to-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">
                        Estado VIP: {{ $vipStatus['is_active'] ? 'ACTIVO' : 'INACTIVO' }}
                    </h3>
                    @if($vipStatus['is_active'])
                        <p class="text-blue-100 dark:text-blue-200">
                            {{ $vipStatus['days_remaining'] }} días restantes
                        </p>
                        <p class="text-sm text-blue-200 dark:text-blue-300">
                            Expira: {{ $vipStatus['expires_at']->format('d/m/Y H:i') }}
                        </p>
                    @else
                        <p class="text-gray-200 dark:text-gray-300">
                            Obtén acceso a contenido exclusivo
                        </p>
                    @endif
                </div>
                
                <div class="text-right">
                    @if($vipStatus['is_active'])
                        <div class="bg-white dark:bg-gray-800 bg-opacity-20 dark:bg-opacity-20 rounded-full px-4 py-2">
                            <span class="text-sm font-semibold">✨ VIP ACTIVO</span>
                        </div>
                    @else
                        <a href="{{ route('gift-codes.redeem') }}" 
                           class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Activar VIP
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('gift-codes.redeem') }}" 
                   class="block p-3 bg-blue-50 dark:bg-blue-900/50 hover:bg-blue-100 dark:hover:bg-blue-900/70 rounded-lg transition-colors">
                    🎁 Canjear Código
                </a>
                <a href="{{ route('gift-codes.my-redemptions') }}" 
                   class="block p-3 bg-purple-50 dark:bg-purple-900/50 hover:bg-purple-100 dark:hover:bg-purple-900/70 rounded-lg transition-colors">
                    📋 Mis Canjes
                </a>
                <a href="{{ route('posts.index') }}" 
                   class="block p-3 bg-green-50 dark:bg-green-900/50 hover:bg-green-100 dark:hover:bg-green-900/70 rounded-lg transition-colors">
                    📄 Ver Posts
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Mi Cuenta</h3>
            <div class="space-y-2">
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Miembro desde:</strong> {{ Auth::user()->created_at->format('M Y') }}</p>
                <p><strong>Códigos canjeados:</strong> {{ Auth::user()->giftCodeRedemptions()->count() }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Enlaces Rápidos</h3>
            <div class="space-y-3">
                <a href="{{ route('profile') }}" class="block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    Editar Perfil →
                </a>
                <a href="{{ route('posts.index') }}" class="block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    Explorar Contenido →
                </a>
                @if($vipStatus['is_active'])
                    <a href="#" class="block text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                        Contenido VIP →
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection