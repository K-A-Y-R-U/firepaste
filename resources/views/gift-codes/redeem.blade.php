@extends('layout')

@section('title', 'Canjear Código de Regalo')

@section('page-title', 'Canjear Código de Regalo')

@section('content')
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-200 mb-2">Canjear Código de Regalo</h1>
        <p class="text-gray-600 dark:text-gray-400">Ingresa tu código para obtener días VIP</p>
    </div>

    <!-- User Info Card -->
    <div class="max-w-md mx-auto mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                </div>
            </div>

            <!-- VIP Status -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado VIP:</span>
                    @if($vipStatus['is_active'])
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Activo ({{ $vipStatus['days_remaining'] }} días restantes)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Inactivo
                        </span>
                    @endif
                </div>
                @if($vipStatus['is_active'] && $vipStatus['expires_at'])
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Expira el {{ $vipStatus['expires_at']->format('d/m/Y H:i') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Redeem Form -->
    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('gift-codes.redeem.process') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Código de Regalo
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="code" 
                            id="code" 
                            placeholder="Ingresa tu código aquí"
                            value="{{ old('code') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent uppercase tracking-widest text-center font-mono text-lg @error('code') border-red-500 @enderror"
                            maxlength="20"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-700 transform hover:scale-105"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        Canjear Código
                    </span>
                </button>
            </form>

            <!-- Help Text -->
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">¿Cómo funciona?</h4>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Los códigos son de un solo uso por usuario</li>
                    <li>• El tiempo VIP se suma a tu membresía actual</li>
                    <li>• Los códigos pueden tener fecha de expiración</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="max-w-md mx-auto mt-6 text-center space-x-4">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
            ← Volver al Dashboard
        </a>
        <span class="text-gray-400 dark:text-gray-600">|</span>
        <a href="{{ route('gift-codes.my-redemptions') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition-colors">
            Mis Canjes →
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    </script>
@endpush